<?php
require_once 'vendor/autoload.php';

use App\Models\WhatsappConversation;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MESSAGES FOR PHONE 918519931876 ===\n\n";

// Search for messages with this phone number (with and without + prefix)
$messages = WhatsappConversation::where('phone', 'LIKE', '%918519931876%')
    ->orWhere('phone', 'LIKE', '%8519931876%')
    ->orderBy('created_at', 'desc')
    ->get();

echo "Found " . $messages->count() . " messages for this number:\n";
echo "======================================\n\n";

foreach ($messages as $msg) {
    echo "ID: " . $msg->id . "\n";
    echo "Phone: " . $msg->phone . "\n";
    echo "Message: " . ($msg->message ?: 'NULL') . "\n";
    echo "Reply: " . ($msg->reply ?: 'NULL') . "\n";
    echo "Created: " . $msg->created_at . "\n";
    echo "------------------------\n";
}

// Check auto-reply keywords
echo "\n=== AUTO-REPLY KEYWORDS ===\n";
$autoReplies = \App\Models\WhatsappAutoReply::all();
foreach ($autoReplies as $reply) {
    echo "Keyword: " . $reply->keyword . " -> " . substr($reply->reply_content, 0, 50) . "...\n";
}

echo "\n=== RECENT ALL MESSAGES (Last 5) ===\n";
$recent = WhatsappConversation::latest()->take(5)->get();
foreach ($recent as $msg) {
    echo "ID: {$msg->id} | Phone: {$msg->phone} | Time: {$msg->created_at}\n";
    echo "Message: " . substr($msg->message ?: 'NULL', 0, 50) . "...\n";
    echo "Reply: " . substr($msg->reply ?: 'NULL', 0, 50) . "...\n";
    echo "------------------------\n";
}
?>
