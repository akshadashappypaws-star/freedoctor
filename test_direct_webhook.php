<?php

echo "===============================================\n";
echo "   WhatsApp Webhook Direct Test\n";
echo "===============================================\n\n";

$webhookUrl = 'https://2e86dea0b513.ngrok-free.app/api/webhook/whatsapp';
$verifyToken = 'FreeDoctor2025SecureToken';
$challenge = 'test_challenge_' . time();

$params = [
    'hub_mode' => 'subscribe',
    'hub_verify_token' => $verifyToken,
    'hub_challenge' => $challenge
];

$testUrl = $webhookUrl . '?' . http_build_query($params);

echo "Testing URL: " . $testUrl . "\n\n";

// Make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'ngrok-skip-browser-warning: true'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Response Code: " . $httpCode . "\n";
echo "Response Body: " . substr($response, 0, 200) . (strlen($response) > 200 ? '...' : '') . "\n";

if ($error) {
    echo "❌ cURL Error: " . $error . "\n";
} elseif ($httpCode === 200 && trim($response) === $challenge) {
    echo "\n✅ SUCCESS! Webhook verification working perfectly!\n";
    echo "🔗 Webhook URL is accessible and responding correctly.\n";
} elseif ($httpCode === 200 && strpos($response, $challenge) !== false) {
    echo "\n✅ Webhook is working but with extra content (ngrok banner)\n";
    echo "🔧 This should still work with Meta's webhook verification.\n";
} else {
    echo "\n❌ Webhook verification failed\n";
    echo "💡 Expected: " . $challenge . "\n";
}

echo "\n===============================================\n";
?>
