<?php
// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Simple test to verify webhook console functionality
echo "Testing Webhook Console Implementation...\n\n";

// 1. Check if .env has the required variables
$webhookUrl = config('services.whatsapp.webhook_url', 'Not set');
$verifyToken = config('services.whatsapp.verify_token', 'Not set');

echo "Environment Variables:\n";
echo "WHATSAPP_WEBHOOK_URL: " . $webhookUrl . "\n";
echo "WHATSAPP_WEBHOOK_VERIFY_TOKEN: " . ($verifyToken !== 'Not set' ? 'Set (hidden)' : 'Not set') . "\n\n";

// 2. Test webhook endpoint locally
$localWebhookUrl = "http://127.0.0.1:8000/api/webhook/whatsapp";
$challenge = "console_test_" . time();

echo "Testing Local Webhook Endpoint:\n";
echo "URL: " . $localWebhookUrl . "\n";
echo "Challenge: " . $challenge . "\n";

$testUrl = $localWebhookUrl . "?hub.mode=subscribe&hub.verify_token=" . urlencode($verifyToken) . "&hub.challenge=" . urlencode($challenge);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
echo "Error: " . ($error ?: "None") . "\n";

if ($httpCode === 200 && trim($response) === $challenge) {
    echo "\n✅ Webhook Console Test PASSED!\n";
    echo "The webhook endpoint is working correctly.\n";
    echo "The console should be able to test and monitor webhooks.\n";
} else {
    echo "\n❌ Webhook Console Test FAILED!\n";
    echo "Please check if the Laravel server is running.\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Webhook Console Features Added:\n";
echo "- Real-time webhook monitoring in admin panel\n";
echo "- Test button using environment variables\n";
echo "- Console-style logging with timestamps\n";
echo "- Auto-refresh every 5 seconds\n";
echo "- Compact design (300px wide)\n";
echo "- Mobile responsive (hidden on small screens)\n";
echo str_repeat("=", 50) . "\n";
?>
