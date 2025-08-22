<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappConversation;

echo "=== CHECKING FOR NEW MESSAGE ===\n";

$latest = WhatsappConversation::latest()->first();
$total = WhatsappConversation::count();

echo "Latest message: ID {$latest->id} | Phone: {$latest->phone} | Time: {$latest->created_at}\n";
echo "Total messages: $total\n\n";

// Check messages from last 10 minutes
$recentMessages = WhatsappConversation::where('created_at', '>', now()->subMinutes(10))
    ->orderBy('created_at', 'desc')
    ->get();

echo "Messages in last 10 minutes: " . $recentMessages->count() . "\n";
foreach ($recentMessages as $msg) {
    echo "- ID: {$msg->id} | Phone: {$msg->phone} | Message: " . substr($msg->message, 0, 30) . "... | Time: {$msg->created_at}\n";
}

echo "\n=== STATUS ===\n";
if ($latest->created_at > now()->subMinutes(2)) {
    echo "✅ New message detected! The webhook is working.\n";
} else {
    echo "❌ No new message in last 2 minutes. Check webhook configuration.\n";
}
?>
