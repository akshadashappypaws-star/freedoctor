<?php
require_once 'vendor/autoload.php';

use App\Models\WhatsappConversation;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total conversations: " . WhatsappConversation::count() . "\n\n";

echo "Latest 5 conversations:\n";
echo "======================\n";

$conversations = WhatsappConversation::latest()->take(5)->get(['id', 'phone', 'message', 'reply', 'created_at']);

foreach ($conversations as $conv) {
    echo "ID: " . $conv->id . "\n";
    echo "Phone: " . $conv->phone . "\n";
    echo "Message: " . ($conv->message ?: 'NULL') . "\n";
    echo "Reply: " . ($conv->reply ?: 'NULL') . "\n";
    echo "Created: " . $conv->created_at . "\n";
    echo "------------------------\n";
}

echo "\nGrouped by phone:\n";
echo "=================\n";

$grouped = WhatsappConversation::selectRaw('phone, COUNT(*) as message_count, MAX(created_at) as last_message')
    ->groupBy('phone')
    ->orderBy('last_message', 'desc')
    ->get();

foreach ($grouped as $group) {
    echo "Phone: " . $group->phone . " | Messages: " . $group->message_count . " | Last: " . $group->last_message . "\n";
}
