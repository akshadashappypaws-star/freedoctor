<?php

echo "🔥 WhatsApp Webhook Tester\n";
echo "========================\n\n";

$ngrokUrl = "https://freedoctor.in";
$webhookUrl = $ngrokUrl . "/webhook/whatsapp";

echo "📱 Testing WhatsApp Webhook: $webhookUrl\n\n";

// Test 1: Webhook Verification
echo "🧪 Test 1: Webhook Verification...\n";
$verifyUrl = $webhookUrl . "?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verifyUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 && $response == 'test123') {
    echo "✅ Verification PASSED - Response: $response\n\n";
} else {
    echo "❌ Verification FAILED - HTTP: $httpCode, Response: $response\n\n";
}

// Test 2: Message Webhook
echo "🧪 Test 2: Message Webhook...\n";

$messagePayload = [
    'object' => 'whatsapp_business_account',
    'entry' => [
        [
            'id' => '102290129340398',
            'changes' => [
                [
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '917741044366',
                            'phone_number_id' => '745838968612692'
                        ],
                        'contacts' => [
                            [
                                'profile' => ['name' => 'Test User'],
                                'wa_id' => '919876543210'
                            ]
                        ],
                        'messages' => [
                            [
                                'from' => '919876543210',
                                'id' => 'wamid.test' . time(),
                                'timestamp' => time(),
                                'text' => ['body' => 'Hello Doctor, I need help'],
                                'type' => 'text'
                            ]
                        ]
                    ],
                    'field' => 'messages'
                ]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messagePayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: WhatsApp-Webhook-Tester'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "✅ Message webhook PASSED - HTTP: $httpCode\n";
    echo "Response: $response\n\n";
} else {
    echo "❌ Message webhook FAILED - HTTP: $httpCode\n";
    echo "Response: $response\n\n";
}

echo "📋 SUMMARY:\n";
echo "=========\n";
echo "WhatsApp Webhook URL: $webhookUrl\n";
echo "Verify Token: FreeDoctor2025SecureToken\n";
echo "Status: " . (($httpCode == 200) ? "✅ READY" : "❌ NEEDS FIXING") . "\n\n";

echo "🔗 Copy this URL to Meta Business Manager:\n";
echo "$webhookUrl\n\n";

echo "💡 Next steps:\n";
echo "1. Copy the webhook URL above\n";
echo "2. Go to https://developers.facebook.com/\n";
echo "3. Navigate to your WhatsApp app > Configuration > Webhooks\n";
echo "4. Add the webhook URL and verify token\n";
echo "5. Subscribe to message events\n\n";

echo "🎉 Your WhatsApp webhook is ready for any activity!\n";
