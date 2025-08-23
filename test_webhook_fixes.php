<?php
// Test the updated webhook endpoint
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Updated Webhook Configuration...\n\n";

$webhookUrl = config('services.whatsapp.webhook_url');
$verifyToken = config('services.whatsapp.verify_token');

echo "Webhook URL: " . $webhookUrl . "\n";
echo "Verify Token: " . ($verifyToken ? 'Set' : 'Not set') . "\n\n";

// Test 1: Direct access (should show status page)
echo "Test 1: Direct access to webhook endpoint\n";
echo "URL: " . $webhookUrl . "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['status'])) {
        echo "✅ Direct access now shows status page!\n";
        echo "Status: " . $data['status'] . "\n";
        echo "Message: " . $data['message'] . "\n";
    } else {
        echo "⚠️ Response received but format unexpected\n";
    }
} else {
    echo "❌ Direct access still failing\n";
}

echo "\n" . str_repeat("-", 50) . "\n";

// Test 2: Verification test
echo "Test 2: Webhook verification test\n";
$challenge = 'test_challenge_' . time();
$testUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=' . urlencode($verifyToken) . '&hub.challenge=' . urlencode($challenge);

echo "URL: " . $testUrl . "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
echo "Expected: " . $challenge . "\n";

if ($httpCode === 200 && trim($response) === $challenge) {
    echo "✅ Webhook verification working correctly!\n";
} else {
    echo "❌ Webhook verification failed\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "Changes Made:\n";
echo "1. Updated webhook controller to show status page on direct access\n";
echo "2. Fixed .env to use https://freedoctor.in/api/webhook/whatsapp\n";
echo "3. Added better error messages for verification failures\n";
echo "4. Added 'Direct' button in webhook console for status check\n";
echo "5. Enhanced logging for debugging\n";
echo str_repeat("=", 50) . "\n";
?>
