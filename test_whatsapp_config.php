<?php
/**
 * WhatsApp Configuration Test Script
 * This script tests if your WhatsApp Business API credentials are properly configured
 */

require_once 'vendor/autoload.php';

// Load environment variables
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with($line, '#')) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

echo "🔍 WhatsApp Business API Configuration Test\n";
echo "==========================================\n\n";

// Check configuration
$accessToken = $_ENV['WHATSAPP_CLOUD_TOKEN'] ?? $_ENV['WHATSAPP_API_KEY'] ?? '';
$phoneNumberId = $_ENV['WHATSAPP_PHONE_NUMBER_ID'] ?? $_ENV['WHATSAPP_CLOUD_PHONE_NUMBER_ID'] ?? '';

echo "📋 Configuration Status:\n";
echo "------------------------\n";

// Check Access Token
echo "Access Token: ";
if (empty($accessToken)) {
    echo "❌ NOT SET\n";
} elseif ($accessToken === 'your_whatsapp_cloud_api_token_here') {
    echo "❌ PLACEHOLDER VALUE (needs real token)\n";
} elseif (strlen($accessToken) < 50) {
    echo "⚠️  SET BUT LOOKS TOO SHORT (current: " . strlen($accessToken) . " chars)\n";
} else {
    echo "✅ SET (length: " . strlen($accessToken) . " chars)\n";
}

// Check Phone Number ID
echo "Phone Number ID: ";
if (empty($phoneNumberId)) {
    echo "❌ NOT SET\n";
} elseif ($phoneNumberId === 'your_phone_number_id_here') {
    echo "❌ PLACEHOLDER VALUE (needs real phone number ID)\n";
} else {
    echo "✅ SET (" . $phoneNumberId . ")\n";
}

echo "\n";

// Test API connectivity if credentials look valid
if (!empty($accessToken) && 
    $accessToken !== 'your_whatsapp_cloud_api_token_here' && 
    !empty($phoneNumberId) && 
    $phoneNumberId !== 'your_phone_number_id_here') {
    
    echo "🌐 Testing API Connectivity:\n";
    echo "----------------------------\n";
    
    // Test WhatsApp Business API endpoint
    $testUrl = "https://graph.facebook.com/v23.0/{$phoneNumberId}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $testUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    echo "API Test URL: " . $testUrl . "\n";
    echo "HTTP Status: " . $httpCode . "\n";
    
    if ($curlError) {
        echo "cURL Error: ❌ " . $curlError . "\n";
    } elseif ($httpCode === 200) {
        echo "API Response: ✅ SUCCESS - WhatsApp API is accessible\n";
        $responseData = json_decode($response, true);
        if (isset($responseData['display_phone_number'])) {
            echo "Phone Number: " . $responseData['display_phone_number'] . "\n";
        }
        if (isset($responseData['verified_name'])) {
            echo "Business Name: " . $responseData['verified_name'] . "\n";
        }
    } elseif ($httpCode === 401) {
        echo "API Response: ❌ UNAUTHORIZED - Check your access token\n";
    } elseif ($httpCode === 404) {
        echo "API Response: ❌ NOT FOUND - Check your phone number ID\n";
    } else {
        echo "API Response: ❌ ERROR (HTTP " . $httpCode . ")\n";
        echo "Response: " . $response . "\n";
    }
} else {
    echo "⚠️  Cannot test API connectivity - configuration incomplete\n";
}

echo "\n";

// Provide setup instructions
echo "📝 Setup Instructions:\n";
echo "----------------------\n";
echo "1. Go to https://developers.facebook.com/\n";
echo "2. Navigate to your WhatsApp Business API app\n";
echo "3. Go to WhatsApp > Getting Started\n";
echo "4. Copy your 'Temporary access token' or generate a permanent one\n";
echo "5. Copy your 'Phone number ID'\n";
echo "6. Update your .env file with these values:\n";
echo "   WHATSAPP_CLOUD_TOKEN=YOUR_ACTUAL_TOKEN\n";
echo "   WHATSAPP_API_KEY=YOUR_ACTUAL_TOKEN\n";
echo "   WHATSAPP_PHONE_NUMBER_ID=YOUR_PHONE_NUMBER_ID\n";
echo "7. Clear Laravel cache: php artisan cache:clear\n";
echo "8. Test again with: php test_whatsapp_config.php\n";

echo "\n";
echo "🔗 Useful Links:\n";
echo "----------------\n";
echo "Facebook Developers: https://developers.facebook.com/\n";
echo "WhatsApp Business API Docs: https://developers.facebook.com/docs/whatsapp/\n";
echo "Getting Started Guide: https://developers.facebook.com/docs/whatsapp/getting-started/\n";

echo "\n✨ Once configured, your webhook monitor will work perfectly!\n";
?>
