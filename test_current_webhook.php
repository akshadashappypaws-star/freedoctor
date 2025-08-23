<?php

echo "=== Testing Current Webhook URL ===\n";
echo "Testing: https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp\n\n";

// Test basic GET request
$ch = curl_init('https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Basic GET - HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test verification
$verifyUrl = 'https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123';
$ch = curl_init($verifyUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$verifyResponse = curl_exec($ch);
$verifyHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Verification - HTTP Code: $verifyHttpCode\n";
echo "Response: $verifyResponse\n";
echo "Expected: test123\n";
echo "Status: " . ($verifyResponse === 'test123' ? 'PASS' : 'FAIL') . "\n\n";

// Test local Laravel server
echo "=== Testing Local Laravel Server ===\n";
$localUrl = 'http://127.0.0.1:8000/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123';
$ch = curl_init($localUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$localResponse = curl_exec($ch);
$localHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Local Laravel - HTTP Code: $localHttpCode\n";
echo "Response: $localResponse\n";
echo "Status: " . ($localResponse === 'test123' ? 'PASS' : 'FAIL') . "\n\n";

// Check recent messages
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "=== Message Status Check ===\n";
$todayCount = WhatsappConversation::whereDate('created_at', date('Y-m-d'))->count();
$yesterdayCount = WhatsappConversation::whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count();
$totalCount = WhatsappConversation::count();

echo "Total messages in database: $totalCount\n";
echo "Messages today: $todayCount\n";
echo "Messages yesterday: $yesterdayCount\n";

if ($todayCount === 0) {
    echo "\n⚠️  WARNING: No messages received today!\n";
    echo "This suggests the webhook isn't receiving new messages.\n\n";
    
    echo "=== Action Required ===\n";
    echo "1. Make sure Meta Business Manager webhook URL is: https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp\n";
    echo "2. Send a WhatsApp message to your business number to test\n";
    echo "3. Check ngrok dashboard at http://localhost:4040 for incoming requests\n";
}
