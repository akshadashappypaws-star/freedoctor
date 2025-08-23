<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "=== Real-Time Message Monitor ===\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n\n";

// Check latest messages
$latest = WhatsappConversation::orderBy('created_at', 'desc')->limit(5)->get();

echo "Latest 5 messages:\n";
foreach ($latest as $msg) {
    echo "- " . $msg->created_at . " | " . $msg->phone . " | " . substr($msg->message ?: $msg->reply, 0, 50) . "\n";
}

echo "\n=== Instructions ===\n";
echo "1. Keep this terminal open\n";
echo "2. Send a WhatsApp message to your business number\n";
echo "3. Run this script again to see if new messages appear\n";
echo "4. The message should appear within seconds if webhook is working\n\n";

echo "Business Number: Your WhatsApp Business number\n";
echo "Webhook URL: https://c74938a2dfce.ngrok-free.app/api/webhook/whatsapp\n";
echo "ngrok Dashboard: http://localhost:4040\n";
