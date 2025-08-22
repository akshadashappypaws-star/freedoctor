<?php

// Test WhatsApp Webhook Connectivity
echo "🔍 Testing WhatsApp Webhook Configuration\n";
echo "=========================================\n\n";

// Check if Laravel is running
echo "1. Testing Laravel server connection...\n";
$laravel_url = "http://127.0.0.1:8000";
$ch = curl_init($laravel_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo "✅ Laravel server is running on port 8000\n\n";
} else {
    echo "❌ Laravel server not accessible on port 8000\n\n";
    exit(1);
}

// Check ngrok tunnel
echo "2. Testing ngrok tunnel...\n";
$ngrok_url = "https://281e39728aeb.ngrok-free.app";
$ch = curl_init($ngrok_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo "✅ ngrok tunnel is working\n\n";
} else {
    echo "❌ ngrok tunnel not accessible (HTTP: $http_code)\n\n";
}

// Test webhook endpoint specifically
echo "3. Testing webhook endpoint...\n";
$webhook_url = "https://281e39728aeb.ngrok-free.app/api/webhook/whatsapp";

// Test GET request (webhook verification)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhook_url . "?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "GET request (verification): HTTP $http_code\n";
if ($http_code == 200 && $result == "test123") {
    echo "✅ Webhook verification is working\n";
} else {
    echo "❌ Webhook verification failed\n";
    echo "Response: $result\n";
}

// Test POST request (message simulation)
echo "\n4. Testing webhook message handling...\n";
$test_data = [
    'entry' => [
        [
            'changes' => [
                [
                    'value' => [
                        'messages' => [
                            [
                                'from' => '919876543210',
                                'id' => 'test_message_' . time(),
                                'text' => ['body' => 'hello'],
                                'timestamp' => time()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhook_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: WhatsApp/1.0'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "POST request (message): HTTP $http_code\n";
if ($http_code == 200) {
    echo "✅ Webhook message handling is working\n";
    echo "Response: $result\n";
} else {
    echo "❌ Webhook message handling failed\n";
    echo "Response: $result\n";
}

echo "\n📋 Summary:\n";
echo "===========\n";
echo "Webhook URL: $webhook_url\n";
echo "Verify Token: FreeDoctor2025SecureToken\n";
echo "Laravel Status: " . ($http_code == 200 ? "✅ Running" : "❌ Down") . "\n";
echo "\n📱 To test with real WhatsApp:\n";
echo "1. Update webhook URL in Meta Business Manager\n";
echo "2. Send a message to your WhatsApp Business number\n";
echo "3. Check your admin panel for new conversations\n";

?>
