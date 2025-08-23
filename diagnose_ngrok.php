<?php

echo "=== ngrok Tunnel Diagnosis ===\n\n";

// Check if ngrok is running
echo "1. Checking if ngrok is running...\n";
$ngrokApi = @file_get_contents('http://localhost:4040/api/tunnels');

if ($ngrokApi === false) {
    echo "❌ ERROR: ngrok is NOT running!\n";
    echo "The ngrok dashboard at http://localhost:4040 is not accessible.\n\n";
    echo "SOLUTION:\n";
    echo "1. Start ngrok with: ngrok http 8000\n";
    echo "2. Keep the ngrok terminal open\n";
    echo "3. Get the new https URL from ngrok dashboard\n";
    echo "4. Update your .env file with the new URL\n";
    exit(1);
}

echo "✅ ngrok is running!\n\n";

// Parse tunnels
$tunnels = json_decode($ngrokApi, true);
echo "2. Current ngrok tunnels:\n";

$httpsUrl = null;
foreach ($tunnels['tunnels'] as $tunnel) {
    echo "- " . $tunnel['proto'] . ": " . $tunnel['public_url'] . " -> " . $tunnel['config']['addr'] . "\n";
    if ($tunnel['proto'] === 'https') {
        $httpsUrl = $tunnel['public_url'];
    }
}

if (!$httpsUrl) {
    echo "❌ ERROR: No HTTPS tunnel found!\n";
    exit(1);
}

echo "\n3. Testing the current ngrok URL...\n";
echo "ngrok HTTPS URL: $httpsUrl\n";

// Test the ngrok URL
$testUrl = $httpsUrl . '/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123';

echo "Testing: $testUrl\n";

$ch = curl_init($testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if ($error) {
    echo "Error: $error\n";
}

if ($httpCode === 200 && $response === 'test123') {
    echo "✅ ngrok tunnel is working correctly!\n\n";
    
    echo "4. Checking .env configuration...\n";
    $envUrl = 'https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp';
    $currentUrl = $httpsUrl . '/api/webhook/whatsapp';
    
    echo "Current .env URL: $envUrl\n";
    echo "Actual ngrok URL: $currentUrl\n";
    
    if ($envUrl !== $currentUrl) {
        echo "❌ MISMATCH: Your .env file has the wrong ngrok URL!\n\n";
        echo "UPDATE REQUIRED:\n";
        echo "1. Update your .env file line 97:\n";
        echo "   WHATSAPP_WEBHOOK_URL=$currentUrl\n";
        echo "2. Update Meta Business Manager with: $currentUrl\n";
    } else {
        echo "✅ .env URL matches ngrok URL\n\n";
        echo "5. NEXT STEPS:\n";
        echo "- Use this URL in Meta Business Manager: $currentUrl\n";
        echo "- When you click 'Verify and Save', you should see the request in ngrok dashboard\n";
        echo "- ngrok dashboard: http://localhost:4040\n";
    }
} else {
    echo "❌ ngrok tunnel is not working properly!\n";
    echo "Expected response: test123\n";
    echo "Actual response: $response\n";
}

echo "\n=== Summary ===\n";
echo "ngrok Dashboard: http://localhost:4040\n";
echo "Meta Webhook URL: $httpsUrl/api/webhook/whatsapp\n";
echo "Verify Token: FreeDoctor2025SecureToken\n";
