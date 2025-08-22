<?php
echo "=== NGROK WEBHOOK URL CHECKER ===\n\n";

// 1. Try to get ngrok URL from API
echo "1. Checking ngrok tunnels...\n";

$ngrokApiUrl = 'http://localhost:4040/api/tunnels';
$context = stream_context_create([
    'http' => [
        'timeout' => 5,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($ngrokApiUrl, false, $context);

if ($response) {
    $data = json_decode($response, true);
    
    if (isset($data['tunnels']) && !empty($data['tunnels'])) {
        echo "✅ Found ngrok tunnels:\n\n";
        
        foreach ($data['tunnels'] as $tunnel) {
            $publicUrl = $tunnel['public_url'];
            $config = $tunnel['config'];
            $addr = isset($config['addr']) ? $config['addr'] : 'N/A';
            
            echo "- URL: $publicUrl\n";
            echo "  Local: $addr\n";
            echo "  Proto: " . $tunnel['proto'] . "\n\n";
            
            // If it's HTTPS and pointing to port 8000, this is our webhook URL
            if (strpos($publicUrl, 'https://') === 0 && strpos($addr, ':8000') !== false) {
                $webhookUrl = $publicUrl . '/api/webhook/whatsapp';
                echo "🎯 YOUR WEBHOOK URL:\n";
                echo "   $webhookUrl\n\n";
                
                // Test webhook verification
                echo "2. Testing webhook verification...\n";
                $verifyUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=freedoctor_webhook_token&hub.challenge=test123';
                
                $verifyContext = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'ignore_errors' => true
                    ]
                ]);
                
                $verifyResponse = @file_get_contents($verifyUrl, false, $verifyContext);
                
                if ($verifyResponse === 'test123') {
                    echo "✅ Webhook verification: WORKING\n";
                    echo "   Response: $verifyResponse\n\n";
                } else {
                    echo "❌ Webhook verification: FAILED\n";
                    echo "   Expected: test123\n";
                    echo "   Got: " . ($verifyResponse ?: 'No response') . "\n\n";
                }
                
                // Test basic webhook endpoint
                echo "3. Testing webhook endpoint accessibility...\n";
                $pingContext = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'timeout' => 10,
                        'ignore_errors' => true
                    ]
                ]);
                
                $pingResponse = @file_get_contents($webhookUrl, false, $pingContext);
                
                if ($pingResponse !== false) {
                    echo "✅ Webhook endpoint: ACCESSIBLE\n";
                    if (strpos($pingResponse, 'Method Not Allowed') !== false) {
                        echo "   Response: GET method not allowed (this is correct - webhook expects POST)\n\n";
                    } else {
                        echo "   Response: " . substr($pingResponse, 0, 100) . "...\n\n";
                    }
                } else {
                    echo "❌ Webhook endpoint: NOT ACCESSIBLE\n\n";
                }
                
                echo "=== CONFIGURATION SUMMARY ===\n";
                echo "WhatsApp Webhook URL: $webhookUrl\n";
                echo "Verify Token: freedoctor_webhook_token\n";
                echo "Phone Number ID: 745838968612692\n\n";
                
                echo "=== NEXT STEPS ===\n";
                echo "1. Copy this webhook URL: $webhookUrl\n";
                echo "2. Go to Meta Business Manager\n";
                echo "3. Navigate to WhatsApp > Configuration\n";
                echo "4. Update webhook URL to: $webhookUrl\n";
                echo "5. Set verify token to: freedoctor_webhook_token\n";
                echo "6. Send a test message from your phone\n\n";
                
                break;
            }
        }
    } else {
        echo "❌ No tunnels found in ngrok response\n";
        echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    }
} else {
    echo "❌ Could not connect to ngrok API at localhost:4040\n";
    echo "Make sure ngrok is running with: ngrok http 8000\n\n";
}

// 4. Check if Laravel server is running
echo "4. Checking Laravel server...\n";
$laravelContext = stream_context_create([
    'http' => [
        'timeout' => 3,
        'ignore_errors' => true
    ]
]);

$laravelResponse = @file_get_contents('http://localhost:8000', false, $laravelContext);

if ($laravelResponse !== false) {
    echo "✅ Laravel server running on localhost:8000\n\n";
} else {
    echo "❌ Laravel server not responding on localhost:8000\n";
    echo "Start with: php artisan serve --port=8000\n\n";
}

// 5. Show recent messages for debugging
echo "5. Recent WhatsApp messages (last 5):\n";

try {
    // Include Laravel bootstrap
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $conversations = \Illuminate\Support\Facades\DB::table('whatsapp_conversations')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    if ($conversations->count() > 0) {
        foreach ($conversations as $conv) {
            $time = date('Y-m-d H:i:s', strtotime($conv->created_at));
            echo "- ID: {$conv->id} | Phone: {$conv->phone_number} | Time: $time\n";
            echo "  Message: " . substr($conv->message_content, 0, 50) . "...\n";
            if ($conv->reply_content) {
                echo "  Reply: " . substr($conv->reply_content, 0, 50) . "...\n";
            }
            echo "\n";
        }
    } else {
        echo "No messages found in database\n\n";
    }
    
} catch (Exception $e) {
    echo "Could not check database: " . $e->getMessage() . "\n\n";
}

echo "=== TROUBLESHOOTING ===\n";
echo "If messages still don't appear:\n";
echo "1. Check Meta Business Manager webhook logs\n";
echo "2. Verify WhatsApp Business phone number\n";
echo "3. Check webhook subscription is active\n";
echo "4. Monitor Laravel logs: tail -f storage/logs/laravel.log\n";
echo "5. Test webhook with: php test_webhook_direct.php\n\n";

echo "Script completed!\n";
?>
