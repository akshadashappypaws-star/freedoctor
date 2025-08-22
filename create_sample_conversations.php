<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WhatsappConversation;

echo "Creating sample WhatsApp conversations...\n";

// Sample conversations
$sampleData = [
    [
        'phone' => '+1234567890',
        'message' => 'Hello, I need help with appointment booking',
        'reply' => 'Hi! I can help you book an appointment. What type of consultation do you need?',
        'reply_type' => 'auto',
        'sent_at' => now()->subHours(2),
        'is_responded' => true,
        'lead_status' => 'active'
    ],
    [
        'phone' => '+9876543210',
        'message' => 'What are your clinic timings?',
        'reply' => 'Our clinic is open Monday to Friday 9 AM to 6 PM, and Saturday 9 AM to 2 PM.',
        'reply_type' => 'auto',
        'sent_at' => now()->subHours(5),
        'is_responded' => true,
        'lead_status' => 'active'
    ],
    [
        'phone' => '+1122334455',
        'message' => 'I want to cancel my appointment',
        'reply' => null,
        'reply_type' => null,
        'sent_at' => null,
        'is_responded' => false,
        'lead_status' => 'pending'
    ]
];

foreach ($sampleData as $data) {
    WhatsappConversation::create($data);
    echo "Created conversation for: " . $data['phone'] . "\n";
}

echo "Sample data created successfully!\n";
echo "Total conversations: " . WhatsappConversation::count() . "\n";

?>
