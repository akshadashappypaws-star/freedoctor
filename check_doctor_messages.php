<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\DoctorMessage;

echo "=== Doctor Messages Check ===\n\n";

$totalMessages = DoctorMessage::count();
echo "Total doctor messages: {$totalMessages}\n\n";

$latestMessages = DoctorMessage::latest()->take(3)->get();
echo "Latest 3 messages:\n";
foreach ($latestMessages as $message) {
    echo "- ID: {$message->id}, Doctor ID: {$message->doctor_id}, Type: {$message->type}\n";
    echo "  Message: " . substr($message->message, 0, 150) . "...\n";
    echo "  Created: {$message->created_at}\n\n";
}
?>
