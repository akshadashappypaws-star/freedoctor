<?php

echo "WhatsApp Webhook Debugging Tool\n";
echo "===============================\n\n";

// Step 1: Check if Laravel server is running
echo "1. Testing Local Laravel Server:\n";
echo "---------------------------------\n";

$local_url = "http://127.0.0.1:8000/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=local_test";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $local_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($http_code === 200 && trim($response) === 'local_test') {
    echo "✅ Laravel server is working correctly\n";
} else {
    echo "❌ Laravel server issue:\n";
    echo "   HTTP Code: $http_code\n";
    echo "   Response: $response\n";
    echo "   Error: " . ($error ?: 'None') . "\n";
    echo "\n⚠️  Fix: Make sure Laravel server is running with 'php artisan serve --port=8000'\n";
}

echo "\n";

// Step 2: Test current ngrok URL from .env
echo "2. Testing ngrok URL from .env:\n";
echo "-------------------------------\n";

$env_file = __DIR__ . '/.env';
$ngrok_url = '';

if (file_exists($env_file)) {
    $env_content = file_get_contents($env_file);
    if (preg_match('/WHATSAPP_WEBHOOK_URL=(.*)/', $env_content, $matches)) {
        $ngrok_url = trim($matches[1]);
    }
}

if (empty($ngrok_url)) {
    echo "❌ No webhook URL found in .env\n";
} else {
    echo "Found URL: $ngrok_url\n";
    
    // Test the ngrok URL
    $test_url = $ngrok_url . "?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=ngrok_test";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $test_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: facebookexternalua',
        'ngrok-skip-browser-warning: true'
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    echo "HTTP Code: $http_code\n";
    echo "Response: " . substr($response, 0, 100) . (strlen($response) > 100 ? '...' : '') . "\n";
    
    if ($http_code === 200 && trim($response) === 'ngrok_test') {
        echo "✅ ngrok URL is working perfectly!\n";
        echo "✅ Meta should be able to verify this webhook\n";
    } elseif ($http_code === 200) {
        echo "⚠️  ngrok URL responds but with extra content\n";
        echo "   This might still work, but check for ngrok banners\n";
    } elseif ($http_code === 0 || $error) {
        echo "❌ ngrok URL is not accessible:\n";
        echo "   Error: " . ($error ?: 'Connection failed') . "\n";
        echo "\n⚠️  Fix: Restart ngrok with 'C:\\ngrok\\ngrok.exe http 8000'\n";
    } else {
        echo "❌ ngrok URL returns HTTP $http_code\n";
        echo "   This means ngrok might be down or URL expired\n";
    }
}

echo "\n";

// Step 3: Generate new ngrok session
echo "3. Instructions to Fix:\n";
echo "-----------------------\n";
echo "If ngrok URL is not working:\n";
echo "1. Stop current ngrok (Ctrl+C)\n";
echo "2. Start new ngrok: C:\\ngrok\\ngrok.exe http 8000\n";
echo "3. Copy the new HTTPS URL\n";
echo "4. Update .env file: WHATSAPP_WEBHOOK_URL=NEW_NGROK_URL/api/webhook/whatsapp\n";
echo "5. Use the updated URL in Meta Business Manager\n\n";

echo "Meta Business Manager Settings:\n";
echo "- Callback URL: [YOUR_NGROK_URL]/api/webhook/whatsapp\n";
echo "- Verify Token: FreeDoctor2025SecureToken\n";
echo "- Subscribe to: messages, message_deliveries, message_reads\n";

?>
