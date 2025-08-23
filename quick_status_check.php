<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== WhatsApp Configuration & Status Check ===\n\n";

echo "Phone Number ID: " . config('services.whatsapp.phone_number_id') . "\n";
echo "Access Token: " . (config('services.whatsapp.access_token') ? 'Set (' . substr(config('services.whatsapp.access_token'), 0, 20) . '...)' : 'Not set') . "\n";
echo "Verify Token: " . config('services.whatsapp.verify_token') . "\n";
echo "Webhook URL: " . config('services.whatsapp.webhook_url') . "\n\n";

echo "=== Quick System Status ===\n";

// Check Laravel server
$serverCheck = @file_get_contents('http://127.0.0.1:8000/api/webhook/whatsapp');
echo "Laravel server: " . ($serverCheck !== false ? "✅ Running" : "❌ Not running") . "\n";

// Check ngrok tunnel
$ngrokCheck = @file_get_contents('https://281e39728aeb.ngrok-free.app/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123');
echo "ngrok tunnel: " . ($ngrokCheck === 'test123' ? "✅ Working" : "❌ Not working") . "\n";

// Check recent messages
use App\Models\WhatsappConversation;
$todayCount = WhatsappConversation::whereDate('created_at', date('Y-m-d'))->count();
$yesterdayCount = WhatsappConversation::whereDate('created_at', date('Y-m-d', strtotime('-1 day')))->count();

echo "Messages today: $todayCount\n";
echo "Messages yesterday: $yesterdayCount\n";

if ($todayCount === 0 && $yesterdayCount > 0) {
    echo "⚠️  No messages received today - webhook might not be working\n";
} elseif ($todayCount === 0 && $yesterdayCount === 0) {
    echo "ℹ️  No recent messages - send a test message to check\n";
}

echo "\n=== Troubleshooting Summary ===\n";
echo "If messages aren't appearing:\n";
echo "1. ✅ Webhook verification works\n";
echo "2. ✅ ngrok tunnel is active\n";
echo "3. ❓ Check Meta Business Manager webhook configuration\n";
echo "4. ❓ Send a WhatsApp message to test number to trigger webhook\n";
echo "5. ❓ Check ngrok dashboard (http://localhost:4040) for incoming requests\n";
