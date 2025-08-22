<?php

echo "WhatsApp Webhook Verification Test\n";
echo "==================================\n\n";

$base_url = 'https://2e86dea0b513.ngrok-free.app/api/webhook/whatsapp';
$verify_token = 'FreeDoctor2025SecureToken';
$challenge = 'test_challenge_123';

// Test with Meta's format (dots)
echo "1. Testing with Meta format (hub.mode, hub.verify_token, hub.challenge):\n";
echo "-------------------------------------------------------------------\n";

$params_dots = [
    'hub.mode' => 'subscribe',
    'hub.verify_token' => $verify_token,
    'hub.challenge' => $challenge
];

$url_dots = $base_url . '?' . http_build_query($params_dots);
echo "URL: " . $url_dots . "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_dots);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['ngrok-skip-browser-warning: true']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: " . $http_code . "\n";
echo "Response Body: " . $response . "\n";

if ($http_code === 200 && trim($response) === $challenge) {
    echo "✅ SUCCESS with Meta format!\n\n";
} else {
    echo "❌ Failed with Meta format\n\n";
}

// Test with underscore format (fallback)
echo "2. Testing with underscore format (hub_mode, hub_verify_token, hub_challenge):\n";
echo "--------------------------------------------------------------------------\n";

$params_underscores = [
    'hub_mode' => 'subscribe',
    'hub_verify_token' => $verify_token,
    'hub_challenge' => $challenge
];

$url_underscores = $base_url . '?' . http_build_query($params_underscores);
echo "URL: " . $url_underscores . "\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_underscores);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['ngrok-skip-browser-warning: true']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: " . $http_code . "\n";
echo "Response Body: " . $response . "\n";

if ($http_code === 200 && trim($response) === $challenge) {
    echo "✅ SUCCESS with underscore format!\n\n";
} else {
    echo "❌ Failed with underscore format\n\n";
}

echo "Meta Business Manager Configuration:\n";
echo "====================================\n";
echo "Callback URL: " . $base_url . "\n";
echo "Verify Token: " . $verify_token . "\n";
echo "Subscribe to: messages, message_deliveries, message_reads\n";
?>
