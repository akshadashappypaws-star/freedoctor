<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappConversation;

echo "=== WhatsApp Message Analysis for +918519931876 ===\n\n";

$phoneNumber = '+918519931876';
$phoneNumberAlt = '918519931876'; // Without +

echo "Searching for phone number: $phoneNumber\n";
echo "Also searching for: $phoneNumberAlt\n\n";

// Get all messages for this phone number (both formats)
$messages = WhatsappConversation::where(function($query) use ($phoneNumber, $phoneNumberAlt) {
    $query->where('phone', $phoneNumber)
          ->orWhere('phone', $phoneNumberAlt);
})->orderBy('created_at', 'asc')->get();

echo "Total messages found: " . $messages->count() . "\n\n";

if ($messages->count() > 0) {
    echo "=== MESSAGE BREAKDOWN ===\n";
    
    // Count incoming vs outgoing messages
    $incomingMessages = $messages->where('reply', null)->whereNotNull('message');
    $outgoingMessages = $messages->whereNotNull('reply');
    $adminMessages = $messages->where('reply_type', 'admin');
    $autoReplyMessages = $messages->where('reply_type', 'auto');
    
    echo "Incoming messages (from user): " . $incomingMessages->count() . "\n";
    echo "Outgoing messages (replies): " . $outgoingMessages->count() . "\n";
    echo "  - Auto replies: " . $autoReplyMessages->count() . "\n";
    echo "  - Admin replies: " . $adminMessages->count() . "\n\n";
    
    echo "=== DETAILED MESSAGE LIST ===\n";
    
    foreach ($messages as $index => $msg) {
        echo "Message " . ($index + 1) . ":\n";
        echo "  Phone: " . $msg->phone . "\n";
        echo "  Created: " . $msg->created_at . "\n";
        echo "  Type: ";
        
        if ($msg->reply_type === 'admin') {
            echo "Admin Reply\n";
            echo "  Content: " . ($msg->reply ?: 'N/A') . "\n";
        } elseif ($msg->reply_type === 'auto') {
            echo "Auto Reply\n";
            echo "  User Message: " . ($msg->message ?: 'N/A') . "\n";
            echo "  Auto Reply: " . ($msg->reply ?: 'N/A') . "\n";
        } elseif ($msg->reply) {
            echo "Reply (Unknown type)\n";
            echo "  User Message: " . ($msg->message ?: 'N/A') . "\n";
            echo "  Reply: " . ($msg->reply ?: 'N/A') . "\n";
        } else {
            echo "Incoming Message\n";
            echo "  Content: " . ($msg->message ?: 'N/A') . "\n";
        }
        
        echo "  Status: " . ($msg->is_responded ? 'Responded' : 'Pending') . "\n";
        echo "  Lead Status: " . ($msg->lead_status ?: 'N/A') . "\n";
        echo "  ---\n";
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Phone Number: $phoneNumber\n";
    echo "Total Messages: " . $messages->count() . "\n";
    echo "Messages from User: " . $incomingMessages->count() . "\n";
    echo "Replies Sent: " . $outgoingMessages->count() . "\n";
    echo "Pending Responses: " . $messages->where('is_responded', false)->count() . "\n";
    
} else {
    echo "No messages found for this phone number.\n";
    echo "Checking if there are any similar numbers in database...\n\n";
    
    // Check for similar phone numbers
    $similarNumbers = WhatsappConversation::where('phone', 'LIKE', '%8519931876%')
                                        ->select('phone')
                                        ->distinct()
                                        ->get();
    
    if ($similarNumbers->count() > 0) {
        echo "Found similar phone numbers:\n";
        foreach ($similarNumbers as $similar) {
            echo "  - " . $similar->phone . "\n";
        }
    } else {
        echo "No similar phone numbers found.\n";
    }
}

echo "\n=== DATABASE VERIFICATION ===\n";
echo "Checking all unique phone numbers in database:\n";

$allPhones = WhatsappConversation::select('phone')
                                ->distinct()
                                ->orderBy('phone')
                                ->get();

echo "Total unique phone numbers: " . $allPhones->count() . "\n";
foreach ($allPhones as $phone) {
    $count = WhatsappConversation::where('phone', $phone->phone)->count();
    echo "  " . $phone->phone . " (" . $count . " messages)\n";
}
