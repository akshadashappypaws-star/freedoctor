<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "=== Latest WhatsApp Messages ===\n\n";

$latestMessages = WhatsappConversation::orderBy('created_at', 'desc')->limit(10)->get();

if ($latestMessages->count() > 0) {
    foreach ($latestMessages as $msg) {
        echo "ID: " . $msg->id . "\n";
        echo "Phone: " . $msg->phone . "\n";
        echo "Created: " . $msg->created_at . "\n";
        echo "Message: " . ($msg->message ?: 'N/A') . "\n";
        echo "Reply: " . ($msg->reply ?: 'N/A') . "\n";
        echo "---\n";
    }
} else {
    echo "No messages found.\n";
}

echo "\n=== Checking for today's messages ===\n";
$today = date('Y-m-d');
$todayMessages = WhatsappConversation::whereDate('created_at', $today)->orderBy('created_at', 'desc')->get();

echo "Messages today ($today): " . $todayMessages->count() . "\n";

if ($todayMessages->count() > 0) {
    foreach ($todayMessages as $msg) {
        echo "Time: " . $msg->created_at->format('H:i:s') . " - Phone: " . $msg->phone . " - " . substr($msg->message ?: $msg->reply ?: 'N/A', 0, 50) . "\n";
    }
}
