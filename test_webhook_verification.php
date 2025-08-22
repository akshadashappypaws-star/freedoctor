<?php

echo "===============================================\n";
echo "   WhatsApp Webhook Verification Test\n";
echo "===============================================\n\n";

// Get the webhook URL from .env
$envFile = __DIR__ . '/.env';
$webhookUrl = '';

if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (preg_match('/WHATSAPP_WEBHOOK_URL=(.*)/', $envContent, $matches)) {
        $webhookUrl = trim($matches[1]);
    }
}

if (empty($webhookUrl)) {
    echo "❌ WHATSAPP_WEBHOOK_URL not found in .env file\n";
    exit(1);
}

echo "Testing webhook URL: " . $webhookUrl . "\n\n";

// Test parameters
$verifyToken = 'FreeDoctor2025SecureToken';
$challenge = 'test_challenge_' . time();

$params = [
    'hub_mode' => 'subscribe',
    'hub_verify_token' => $verifyToken,
    'hub_challenge' => $challenge
];

$testUrl = $webhookUrl . '?' . http_build_query($params);

echo "Making verification request...\n";

// Make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'WhatsApp/1.0 (+https://developers.facebook.com/docs/whatsapp)');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Response Code: " . $httpCode . "\n";
echo "Response Body: " . $response . "\n";

if ($error) {
    echo "❌ cURL Error: " . $error . "\n";
} elseif ($httpCode === 200 && $response === $challenge) {
    echo "\n✅ SUCCESS! Webhook verification working perfectly!\n";
    echo "📝 Next steps:\n";
    echo "   1. Copy this URL: " . $webhookUrl . "\n";
    echo "   2. Go to Meta Business Manager\n";
    echo "   3. Configure webhook with this URL\n";
    echo "   4. Use verify token: " . $verifyToken . "\n";
} elseif ($httpCode === 404) {
    echo "\n❌ Webhook endpoint not found (404)\n";
    echo "💡 Check if:\n";
    echo "   - Laravel server is running (php artisan serve --port=8000)\n";
    echo "   - ngrok tunnel is active\n";
    echo "   - URL in .env is correct\n";
} else {
    echo "\n❌ Webhook verification failed\n";
    echo "💡 Expected response: " . $challenge . "\n";
    echo "💡 Got response: " . $response . "\n";
}

echo "\n===============================================\n";
?>
