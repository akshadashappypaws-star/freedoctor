<?php

/**
 * Complete Webhook Testing Dashboard
 * Tests all webhook endpoints and provides detailed results
 */

echo "🚀 COMPLETE WEBHOOK TESTING DASHBOARD\n";
echo "=====================================\n\n";

$baseUrl = "https://freedoctor.in";

$webhooks = [
    'WhatsApp' => [
        'url' => $baseUrl . '/webhook/whatsapp',
        'verify_url' => $baseUrl . '/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123',
        'purpose' => 'Receive WhatsApp messages, auto-replies, message status updates',
        'events' => ['messages', 'message_deliveries', 'message_reads', 'message_echoes']
    ],
    'Razorpay' => [
        'url' => $baseUrl . '/webhook/razorpay',
        'purpose' => 'Handle payment events, payouts, account validations',
        'events' => ['payment.captured', 'payment.failed', 'order.paid', 'payout.processed']
    ],
    'Payment' => [
        'url' => $baseUrl . '/webhook/payment',
        'purpose' => 'Generic payment gateway events, transaction updates',
        'events' => ['payment.captured', 'payment.failed', 'payment.authorized']
    ],
    'General' => [
        'url' => $baseUrl . '/webhook/general',
        'purpose' => 'Custom integrations, third-party services, API callbacks',
        'events' => ['user.registered', 'campaign.created', 'appointment.booked']
    ],
    'Test' => [
        'url' => $baseUrl . '/webhook/test',
        'purpose' => 'Testing webhook functionality, debugging, development',
        'events' => ['test_event']
    ]
];

echo "📋 WEBHOOK ENDPOINTS OVERVIEW:\n";
echo str_repeat("=", 60) . "\n\n";

foreach ($webhooks as $name => $config) {
    echo "📌 {$name} Webhook:\n";
    echo "   URL: {$config['url']}\n";
    echo "   Purpose: {$config['purpose']}\n";
    echo "   Events: " . implode(', ', $config['events']) . "\n";
    if (isset($config['verify_url'])) {
        echo "   Verify: {$config['verify_url']}\n";
    }
    echo "\n";
}

echo "🧪 RUNNING TESTS...\n";
echo str_repeat("=", 60) . "\n\n";

$results = [];

// Test WhatsApp Webhook Verification
echo "📱 Testing WhatsApp Webhook Verification...\n";
$verifyUrl = $webhooks['WhatsApp']['verify_url'];
$response = testWebhook('GET', $verifyUrl);
$whatsappVerify = $response['status'] === 200 && trim($response['body']) === 'test123';
$results['WhatsApp Verification'] = $whatsappVerify;
echo $whatsappVerify ? "✅ PASSED\n" : "❌ FAILED - " . $response['body'] . "\n";

// Test WhatsApp Message Webhook
echo "📱 Testing WhatsApp Message Webhook...\n";
$whatsappPayload = [
    'object' => 'whatsapp_business_account',
    'entry' => [[
        'id' => '102290129340398',
        'changes' => [[
            'value' => [
                'messaging_product' => 'whatsapp',
                'metadata' => [
                    'display_phone_number' => '917741044366',
                    'phone_number_id' => '745838968612692'
                ],
                'contacts' => [[
                    'profile' => ['name' => 'Test User'],
                    'wa_id' => '919876543210'
                ]],
                'messages' => [[
                    'from' => '919876543210',
                    'id' => 'wamid.test' . time(),
                    'timestamp' => time(),
                    'text' => ['body' => 'Hello Doctor, I need help'],
                    'type' => 'text'
                ]]
            ],
            'field' => 'messages'
        ]]
    ]]
];
$response = testWebhook('POST', $webhooks['WhatsApp']['url'], $whatsappPayload);
$whatsappMessage = $response['status'] === 200;
$results['WhatsApp Message'] = $whatsappMessage;
echo $whatsappMessage ? "✅ PASSED\n" : "❌ FAILED - HTTP " . $response['status'] . "\n";

// Test Razorpay Webhook
echo "💰 Testing Razorpay Webhook...\n";
$razorpayPayload = [
    'entity' => 'event',
    'account_id' => 'acc_test123',
    'event' => 'payment.captured',
    'payload' => [
        'payment' => [
            'entity' => [
                'id' => 'pay_test12345',
                'amount' => 50000,
                'currency' => 'INR',
                'status' => 'captured'
            ]
        ]
    ]
];
$response = testWebhook('POST', $webhooks['Razorpay']['url'], $razorpayPayload);
$razorpay = $response['status'] === 200;
$results['Razorpay'] = $razorpay;
echo $razorpay ? "✅ PASSED\n" : "❌ FAILED - HTTP " . $response['status'] . "\n";

// Test Payment Webhook
echo "💳 Testing Payment Webhook...\n";
$paymentPayload = [
    'event' => 'payment.captured',
    'data' => [
        'id' => 'pay_general123',
        'amount' => 25000,
        'status' => 'captured'
    ]
];
$response = testWebhook('POST', $webhooks['Payment']['url'], $paymentPayload);
$payment = $response['status'] === 200;
$results['Payment'] = $payment;
echo $payment ? "✅ PASSED\n" : "❌ FAILED - HTTP " . $response['status'] . "\n";

// Test General Webhook
echo "🔧 Testing General Webhook...\n";
$generalPayload = [
    'event' => 'test_event',
    'data' => [
        'message' => 'Test webhook functionality',
        'timestamp' => date('Y-m-d H:i:s'),
        'source' => 'webhook_tester'
    ]
];
$response = testWebhook('POST', $webhooks['General']['url'], $generalPayload);
$general = $response['status'] === 200;
$results['General'] = $general;
echo $general ? "✅ PASSED\n" : "❌ FAILED - HTTP " . $response['status'] . "\n";

// Test Test Endpoint
echo "🧪 Testing Test Endpoint...\n";
$testPayload = ['test' => true, 'timestamp' => time()];
$response = testWebhook('POST', $webhooks['Test']['url'], $testPayload);
$test = $response['status'] === 200;
$results['Test Endpoint'] = $test;
echo $test ? "✅ PASSED\n" : "❌ FAILED - HTTP " . $response['status'] . "\n";

echo "\n" . str_repeat("=", 60) . "\n";
echo "📊 FINAL RESULTS:\n";
echo str_repeat("=", 60) . "\n\n";

$passed = 0;
$total = count($results);

foreach ($results as $test => $success) {
    $status = $success ? "✅ PASS" : "❌ FAIL";
    echo sprintf("%-25s %s\n", $test . ":", $status);
    if ($success) $passed++;
}

echo "\n";
echo "🎯 Overall Score: {$passed}/{$total} webhooks working\n";
echo "📈 Success Rate: " . round(($passed / $total) * 100) . "%\n\n";

if ($passed === $total) {
    echo "🎉 ALL WEBHOOKS ARE WORKING PERFECTLY!\n";
    echo "🚀 Your webhook system is ready to capture ANY activity!\n\n";
} else {
    echo "⚠️  Some webhooks need attention.\n";
    echo "💡 Check Laravel logs for detailed error information.\n\n";
}

echo "📋 CONFIGURATION URLS:\n";
echo "======================\n";
echo "📱 WhatsApp (Meta): https://developers.facebook.com/\n";
echo "💰 Razorpay: https://dashboard.razorpay.com/app/webhooks\n";
echo "📊 Monitor: " . $baseUrl . "/webhook/monitor\n";
echo "📁 Logs: storage/logs/laravel.log\n\n";

echo "🔗 WEBHOOK URLS TO COPY:\n";
echo "========================\n";
foreach ($webhooks as $name => $config) {
    echo "{$name}: {$config['url']}\n";
}

echo "\n🎯 All webhooks tested and ready for production use!\n";

/**
 * Test webhook function
 */
function testWebhook($method, $url, $data = null) {
    $ch = curl_init();
    
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'User-Agent: FreeDoctor-Webhook-Tester/2.0'
        ]
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'status' => $httpCode,
        'body' => $response ?: $error,
        'error' => !empty($error)
    ];
}
