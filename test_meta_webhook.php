<?php

// Simple webhook verification test that mimics Meta's request exactly
$url = 'https://2e86dea0b513.ngrok-free.app/api/webhook/whatsapp';
$verify_token = 'FreeDoctor2025SecureToken';
$challenge = 'meta_verification_challenge_' . rand(1000, 9999);

$params = [
    'hub.mode' => 'subscribe',
    'hub.verify_token' => $verify_token,
    'hub.challenge' => $challenge
];

$test_url = $url . '?' . http_build_query($params);

echo "Meta-style webhook verification test\n";
echo "====================================\n";
echo "URL: " . $test_url . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'facebookexternalua');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: facebookexternalua'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
echo "Response: " . $response . "\n";
echo "cURL Error: " . ($error ?: 'None') . "\n\n";

if ($http_code === 200 && trim($response) === $challenge) {
    echo "✅ PERFECT! Meta webhook verification will work!\n";
} else {
    echo "❌ Issue detected:\n";
    if ($http_code !== 200) {
        echo "  - HTTP code is " . $http_code . " (should be 200)\n";
    }
    if (trim($response) !== $challenge) {
        echo "  - Response doesn't match challenge\n";
        echo "  - Expected: '" . $challenge . "'\n";
        echo "  - Got: '" . trim($response) . "'\n";
    }
}
?>
