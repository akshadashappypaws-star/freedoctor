<?php
echo "=== SIMPLE WEBHOOK TESTER ===\n\n";

// You need to replace this with your actual ngrok URL from http://localhost:4040
$ngrokUrl = "REPLACE_WITH_YOUR_NGROK_URL"; // e.g., "https://abc123.ngrok-free.app"

if ($ngrokUrl === "REPLACE_WITH_YOUR_NGROK_URL") {
    echo "❌ SETUP REQUIRED:\n";
    echo "1. Open http://localhost:4040 in your browser\n";
    echo "2. Copy the HTTPS URL (e.g., https://abc123.ngrok-free.app)\n";
    echo "3. Edit this file and replace 'REPLACE_WITH_YOUR_NGROK_URL' with your actual URL\n";
    echo "4. Run this script again\n\n";
    exit;
}

$webhookUrl = $ngrokUrl . '/api/webhook/whatsapp';

echo "🎯 Testing Webhook URL: $webhookUrl\n\n";

// Test 1: Webhook verification
echo "1. Testing webhook verification...\n";
$verifyUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=freedoctor_webhook_token&hub.challenge=test123';

$context = stream_context_create([
    'http' => [
        'timeout' => 15,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($verifyUrl, false, $context);

if ($response === 'test123') {
    echo "✅ Webhook verification: WORKING\n";
    echo "   Expected: test123\n";
    echo "   Got: $response\n\n";
} else {
    echo "❌ Webhook verification: FAILED\n";
    echo "   Expected: test123\n";
    echo "   Got: " . ($response ?: 'No response') . "\n\n";
}

// Test 2: Webhook POST endpoint
echo "2. Testing webhook POST endpoint...\n";

$testData = json_encode([
    'entry' => [
        [
            'id' => '123456789',
            'changes' => [
                [
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '918123456789',
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
                                'id' => 'test_message_' . time(),
                                'timestamp' => time(),
                                'text' => ['body' => 'Test message from webhook tester'],
                                'type' => 'text'
                            ]
                        ]
                    ],
                    'field' => 'messages'
                ]
            ]
        ]
    ]
]);

$postContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $testData,
        'timeout' => 15,
        'ignore_errors' => true
    ]
]);

$postResponse = @file_get_contents($webhookUrl, false, $postContext);

if ($postResponse !== false) {
    echo "✅ Webhook POST: ACCESSIBLE\n";
    echo "   Response: " . substr($postResponse, 0, 200) . "\n\n";
} else {
    echo "❌ Webhook POST: FAILED\n";
    echo "   No response received\n\n";
}

echo "=== CONFIGURATION FOR META BUSINESS MANAGER ===\n";
echo "Webhook URL: $webhookUrl\n";
echo "Verify Token: freedoctor_webhook_token\n";
echo "Webhook Fields: messages\n";
echo "Phone Number ID: 745838968612692\n\n";

echo "=== NEXT STEPS ===\n";
echo "1. Go to Meta Business Manager: https://business.facebook.com\n";
echo "2. Navigate to your WhatsApp Business account\n";
echo "3. Go to WhatsApp > Configuration\n";
echo "4. Update the webhook URL to: $webhookUrl\n";
echo "5. Set verify token to: freedoctor_webhook_token\n";
echo "6. Subscribe to 'messages' webhook field\n";
echo "7. Save the configuration\n";
echo "8. Send a test message from your phone to your WhatsApp Business number\n\n";

echo "=== TROUBLESHOOTING ===\n";
echo "If it still doesn't work:\n";
echo "- Make sure you're sending messages TO your WhatsApp Business number\n";
echo "- Check the phone number is correctly configured in Meta\n";
echo "- Verify the webhook subscription is active\n";
echo "- Monitor Laravel logs: tail -f storage/logs/laravel.log\n\n";
?>
