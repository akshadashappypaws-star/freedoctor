<?php
// Test webhook endpoint directly
$url = "http://127.0.0.1:8000/api/webhook/whatsapp";
$verify_token = "FreeDoctor2025SecureToken";
$challenge = "test_challenge_local";

// Test with Meta format
$test_url = $url . "?hub.mode=subscribe&hub.verify_token=" . urlencode($verify_token) . "&hub.challenge=" . urlencode($challenge);

echo "Testing local webhook endpoint:\n";
echo "URL: " . $test_url . "\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $test_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $http_code . "\n";
echo "Response: " . $response . "\n";
echo "Error: " . ($error ?: "None") . "\n";

if ($http_code === 200 && trim($response) === $challenge) {
    echo "\n✅ Local webhook verification SUCCESSFUL!\n";
    echo "The webhook code is working correctly.\n";
    echo "Issue is likely with ngrok or Meta configuration.\n";
} else {
    echo "\n❌ Local webhook verification FAILED!\n";
    echo "Issue is with the webhook code itself.\n";
}
?>
