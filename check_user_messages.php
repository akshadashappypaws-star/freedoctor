<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\UserMessage;

echo "=== User Messages Check ===\n\n";

$totalMessages = UserMessage::count();
echo "Total user messages: {$totalMessages}\n\n";

$latestMessages = UserMessage::latest()->take(3)->get();
echo "Latest 3 user messages:\n";
foreach ($latestMessages as $message) {
    echo "- ID: {$message->id}, User ID: {$message->user_id}, Type: {$message->type}\n";
    echo "  Message: " . substr($message->message, 0, 100) . "...\n";
    echo "  Created: {$message->created_at}\n\n";
}
?>
