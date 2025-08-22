<?php
require_once 'vendor/autoload.php';

use App\Models\WhatsappConversation;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating test conversation with both messages and replies...\n";

$testPhone = '919999888888';

// Create a conversation sequence
$conversations = [
    [
        'phone' => $testPhone,
        'message' => 'Hello',
        'reply' => 'Hello! Welcome to FreeDoctor. How can I help you today?',
        'reply_type' => 'text',
        'is_responded' => true,
        'lead_status' => 'active',
        'created_at' => now()->subMinutes(30),
        'updated_at' => now()->subMinutes(30)
    ],
    [
        'phone' => $testPhone,
        'message' => 'I need help with booking an appointment',
        'reply' => 'To book an appointment, please provide your preferred date and time. Our team will confirm availability.',
        'reply_type' => 'text',
        'is_responded' => true,
        'lead_status' => 'active',
        'created_at' => now()->subMinutes(25),
        'updated_at' => now()->subMinutes(25)
    ],
    [
        'phone' => $testPhone,
        'message' => 'Tomorrow at 2 PM would be great',
        'reply' => null,
        'reply_type' => null,
        'is_responded' => false,
        'lead_status' => 'pending',
        'created_at' => now()->subMinutes(20),
        'updated_at' => now()->subMinutes(20)
    ]
];

foreach ($conversations as $conv) {
    WhatsappConversation::create($conv);
    echo "Created conversation entry for: " . $conv['phone'] . "\n";
}

echo "\nTest conversation created successfully!\n";
echo "Visit: http://127.0.0.1:8000/admin/whatsapp/conversations/" . $testPhone . "\n";
echo "Total conversations for " . $testPhone . ": " . WhatsappConversation::where('phone', $testPhone)->count() . "\n";
