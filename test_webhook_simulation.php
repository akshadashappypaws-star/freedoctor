<?php

echo "=== Testing Webhook Reception ===\n\n";

// Test 1: Simulate a WhatsApp webhook POST request
echo "1. Testing webhook with simulated WhatsApp message...\n";

$webhookData = [
    'object' => 'whatsapp_business_account',
    'entry' => [
        [
            'id' => '1588252012149592',
            'changes' => [
                [
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '16505551234',
                            'phone_number_id' => '745838968612692'
                        ],
                        'messages' => [
                            [
                                'from' => '+918519931876',
                                'id' => 'wamid.test_' . time(),
                                'timestamp' => time(),
                                'text' => ['body' => 'Test message from webhook simulation - ' . date('H:i:s')],
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

// Send POST request to local webhook
$ch = curl_init('http://127.0.0.1:8000/api/webhook/whatsapp');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Hub-Signature-256: sha256=test'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
if ($error) {
    echo "Error: $error\n";
}

// Test 2: Check if message was saved to database
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "\n2. Checking if message was saved to database...\n";

$latestMessage = WhatsappConversation::orderBy('created_at', 'desc')->first();
if ($latestMessage) {
    echo "Latest message:\n";
    echo "- Phone: " . $latestMessage->phone . "\n";
    echo "- Created: " . $latestMessage->created_at . "\n";
    echo "- Message: " . substr($latestMessage->message ?: 'N/A', 0, 50) . "\n";
    
    // Check if it's from our test
    if ($latestMessage->created_at->diffInMinutes(now()) < 1) {
        echo "✅ SUCCESS: Test message was saved!\n";
    } else {
        echo "⚠️  No recent message found from our test\n";
    }
} else {
    echo "❌ No messages found in database\n";
}

// Test 3: Test ngrok tunnel directly
echo "\n3. Testing ngrok tunnel...\n";

$ngrokUrl = 'https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp';
$ch = curl_init($ngrokUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Hub-Signature-256: sha256=test'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$ngrokResponse = curl_exec($ch);
$ngrokHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$ngrokError = curl_error($ch);
curl_close($ch);

echo "ngrok HTTP Code: $ngrokHttpCode\n";
echo "ngrok Response: $ngrokResponse\n";
if ($ngrokError) {
    echo "ngrok Error: $ngrokError\n";
}

echo "\n=== Diagnosis ===\n";

if ($httpCode == 200 && $ngrokHttpCode == 200) {
    echo "✅ Webhook endpoint is working correctly!\n";
    echo "❌ Problem: Meta Business Manager is not sending requests\n\n";
    echo "Action required:\n";
    echo "1. Go to Meta Business Manager webhook configuration\n";
    echo "2. Update webhook URL to: https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp\n";
    echo "3. Make sure 'messages' field is subscribed\n";
    echo "4. Save and test the webhook\n";
} else {
    echo "❌ Webhook endpoint has issues\n";
    echo "Local endpoint status: $httpCode\n";
    echo "ngrok endpoint status: $ngrokHttpCode\n";
}
