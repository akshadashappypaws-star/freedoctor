<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappConversation;
use Illuminate\Support\Facades\DB;

echo "Creating Real-time Test Messages...\n";

// Create messages for different phone numbers to test real-time updates
$testMessages = [
    [
        'phone' => '919123456789',
        'message' => 'Hi, I just sent you a new message! This should appear in real-time.',
        'message_id' => 'wamid.REALTIME_' . time() . '_1',
        'sent_at' => now(),
        'is_responded' => false,
        'lead_status' => 'interested'
    ],
    [
        'phone' => '+919876543210',
        'message' => 'Another message for testing real-time updates!',
        'message_id' => 'wamid.REALTIME_' . time() . '_2',
        'sent_at' => now(),
        'is_responded' => false,
        'lead_status' => 'qualified'
    ],
    [
        'phone' => '918888999999',
        'message' => 'Hello from a completely new user! This should create a new conversation.',
        'message_id' => 'wamid.REALTIME_' . time() . '_3',
        'sent_at' => now(),
        'is_responded' => false,
        'lead_status' => 'new'
    ]
];

foreach ($testMessages as $index => $testData) {
    try {
        $conversation = WhatsappConversation::create($testData);
        echo "Message " . ($index + 1) . " created - Phone: {$conversation->phone}, ID: {$conversation->id}\n";
        
        // Add a small delay between messages
        sleep(1);
    } catch (Exception $e) {
        echo "Error creating message " . ($index + 1) . ": " . $e->getMessage() . "\n";
    }
}

echo "\nTest messages created! Check your admin interface for real-time updates.\n";
echo "These messages should appear automatically in the conversations list.\n";

// Show current message counts
echo "\nCurrent conversation counts:\n";
$phoneCounts = WhatsappConversation::select('phone')
    ->selectRaw('COUNT(*) as count')
    ->groupBy('phone')
    ->orderBy('count', 'desc')
    ->get();

foreach ($phoneCounts as $phoneCount) {
    echo "Phone: {$phoneCount->phone} - Messages: {$phoneCount->count}\n";
}

echo "\nReal-time test completed!\n";
