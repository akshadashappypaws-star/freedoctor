<?php

// Test WhatsApp webhook verification using web route
$webhook_url = 'https://freedoctor.world/webhook/whatsapp';
$verify_token = 'FreeDoctor2025SecureToken';

// Simulate Meta's verification request
$params = [
    'hub_mode' => 'subscribe',
    'hub_verify_token' => $verify_token,
    'hub_challenge' => 'test_challenge_12345'
];

$url = $webhook_url . '?' . http_build_query($params);

echo "Testing web route webhook verification...\n";
echo "URL: " . $url . "\n\n";

// Make the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Response code: " . $http_code . "\n";
echo "Response body: " . $response . "\n";

if ($error) {
    echo "cURL Error: " . $error . "\n";
}

if ($response === 'test_challenge_12345') {
    echo "\n✅ Webhook verification SUCCESSFUL!\n";
    echo "Use this URL in Meta: " . $webhook_url . "\n";
} else {
    echo "\n❌ Webhook verification FAILED!\n";
}
?>
