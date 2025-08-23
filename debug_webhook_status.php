<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Log all incoming requests to this file
$logFile = __DIR__ . '/webhook_debug.log';

echo "=== Webhook Debug Info ===\n\n";

// Check if webhook_debug.log exists and show recent entries
if (file_exists($logFile)) {
    echo "Recent webhook requests:\n";
    $lines = file($logFile);
    $recentLines = array_slice($lines, -20); // Last 20 lines
    foreach ($recentLines as $line) {
        echo $line;
    }
} else {
    echo "No webhook debug log found yet.\n";
}

echo "\n=== Testing webhook endpoint manually ===\n";

// Test webhook endpoint directly
$webhookUrl = 'http://127.0.0.1:8000/api/webhook/whatsapp';

echo "Testing GET request to: $webhookUrl\n";
$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test verification
$verifyUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123';
echo "Testing verification: $verifyUrl\n";

$ch = curl_init($verifyUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$verifyResponse = curl_exec($ch);
$verifyHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Verify HTTP Code: $verifyHttpCode\n";
echo "Verify Response: $verifyResponse\n";
echo "Expected: test123\n";
echo "Status: " . ($verifyResponse === 'test123' ? 'PASS' : 'FAIL') . "\n\n";

echo "=== Current configuration ===\n";
echo "Webhook URL: https://281e39728aeb.ngrok-free.app/api/webhook/whatsapp\n";
echo "Verify Token: FreeDoctor2025SecureToken\n";
echo "Phone Number ID: " . env('WHATSAPP_PHONE_NUMBER_ID', 'Not set') . "\n";
echo "Access Token: " . (env('WHATSAPP_ACCESS_TOKEN') ? 'Set' : 'Not set') . "\n";
