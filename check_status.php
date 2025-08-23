<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "=== Message Status Check ===\n";
$today = WhatsappConversation::whereDate('created_at', date('Y-m-d'))->count();
$total = WhatsappConversation::count();

echo "Total messages: $total\n";
echo "Messages today: $today\n";

$latest = WhatsappConversation::orderBy('created_at', 'desc')->first();
if ($latest) {
    echo "Latest message: " . $latest->created_at . " - " . $latest->phone . "\n";
    echo "Message content: " . substr($latest->message ?: $latest->reply, 0, 100) . "\n";
}
