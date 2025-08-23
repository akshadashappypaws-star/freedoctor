<?php

echo "=== NGROK URL DETECTOR ===\n\n";

// Get ngrok URL from API
$ngrokApi = @file_get_contents('http://localhost:4040/api/tunnels');

if ($ngrokApi) {
    $tunnels = json_decode($ngrokApi, true);
    
    if (isset($tunnels['tunnels']) && count($tunnels['tunnels']) > 0) {
        foreach ($tunnels['tunnels'] as $tunnel) {
            if ($tunnel['proto'] === 'https') {
                $ngrokUrl = $tunnel['public_url'];
                echo "✅ Current ngrok URL: $ngrokUrl\n";
                
                // Test the webhook URL
                $webhookUrl = $ngrokUrl . '/api/webhook/whatsapp';
                echo "✅ Webhook URL: $webhookUrl\n\n";
                
                // Test verification
                $verifyUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123';
                
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'ignore_errors' => true
                    ]
                ]);
                
                $response = @file_get_contents($verifyUrl, false, $context);
                
                if ($response === 'test123') {
                    echo "✅ Webhook verification: WORKING\n";
                    echo "✅ This URL is ready to use in Meta Business Manager!\n\n";
                } else {
                    echo "❌ Webhook verification: FAILED\n";
                    echo "Response: " . ($response ?: 'No response') . "\n\n";
                }
                
                // Update .env file
                $envFile = __DIR__ . '/.env';
                $envContent = file_get_contents($envFile);
                
                // Replace the webhook URL in .env
                $newEnvContent = preg_replace(
                    '/WHATSAPP_WEBHOOK_URL=.*/',
                    'WHATSAPP_WEBHOOK_URL=' . $webhookUrl,
                    $envContent
                );
                
                if ($newEnvContent !== $envContent) {
                    file_put_contents($envFile, $newEnvContent);
                    echo "✅ Updated .env file with new webhook URL\n";
                } else {
                    echo "ℹ️  .env file already has correct URL\n";
                }
                
                echo "\n=== NEXT STEPS ===\n";
                echo "1. Copy this webhook URL: $webhookUrl\n";
                echo "2. Go to Meta Business Manager\n";
                echo "3. Update webhook URL to: $webhookUrl\n";
                echo "4. Verify token: FreeDoctor2025SecureToken\n";
                echo "5. Subscribe to 'messages' events\n";
                echo "6. Send a test WhatsApp message\n";
                
                break;
            }
        }
    } else {
        echo "❌ No ngrok tunnels found\n";
        echo "Start ngrok with: ngrok http 8000\n";
    }
} else {
    echo "❌ Cannot connect to ngrok dashboard\n";
    echo "Make sure ngrok is running: ngrok http 8000\n";
}

echo "\n=== TESTING ADMIN PANEL ===\n";

// Check if we can access admin panel
$adminUrls = [
    'http://127.0.0.1:8000/admin/whatsapp/conversations/+918519931876',
    'http://127.0.0.1:8000/admin/whatsapp/conversations/918519931876'
];

foreach ($adminUrls as $url) {
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    $status = $response !== false ? "✅ Accessible" : "❌ Error";
    echo "Admin panel ($url): $status\n";
}

// Check recent messages
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

$recentMessages = WhatsappConversation::where('phone', 'LIKE', '%8519931876%')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(3)
                                    ->get();

echo "\n=== RECENT MESSAGES FOR +918519931876 ===\n";
foreach ($recentMessages as $msg) {
    echo "ID: {$msg->id} | Phone: {$msg->phone} | Created: {$msg->created_at} | Message: " . substr($msg->message ?: $msg->reply ?: 'N/A', 0, 50) . "\n";
}
