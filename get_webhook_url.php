<?php
echo "=== NGROK WEBHOOK URL CHECKER ===\n\n";

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
        echo "✅ Ngrok is running!\n\n";
        
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
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
                echo "   $webhookUrl\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
                
                echo "📋 COPY THIS EXACT URL TO META BUSINESS MANAGER:\n";
                echo "   Webhook URL: $webhookUrl\n";
                echo "   Verify Token: freedoctor_webhook_token\n\n";
                
                echo "🔧 CONFIGURATION STEPS:\n";
                echo "   1. Go to Meta Business Manager (https://business.facebook.com)\n";
                echo "   2. Navigate to WhatsApp > Configuration\n";
                echo "   3. Update webhook URL to: $webhookUrl\n";
                echo "   4. Set verify token to: freedoctor_webhook_token\n";
                echo "   5. Subscribe to 'messages' field\n";
                echo "   6. Save configuration\n\n";
                
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
    echo "🚀 TO START NGROK:\n";
    echo "   1. Open a new terminal\n";
    echo "   2. Run: ngrok http 8000\n";
    echo "   3. Run this script again to get the webhook URL\n\n";
}

echo "=== ADDITIONAL INFO ===\n";
echo "Current PHP server should be running on: http://localhost:8000\n";
echo "Ngrok dashboard available at: http://localhost:4040\n";
echo "Webhook endpoint path: /api/webhook/whatsapp\n";
echo "Verify token: freedoctor_webhook_token\n\n";

// Check if Laravel server is accessible
$laravelCheck = @file_get_contents('http://localhost:8000', false, stream_context_create([
    'http' => ['timeout' => 3, 'ignore_errors' => true]
]));

if ($laravelCheck !== false) {
    echo "✅ Laravel server is responding on localhost:8000\n";
} else {
    echo "❌ Laravel server not responding on localhost:8000\n";
    echo "   Start with: php artisan serve --port=8000\n";
}

echo "\nScript completed!\n";
?>
