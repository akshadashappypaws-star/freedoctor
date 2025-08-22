<?php
require_once 'vendor/autoload.php';

use App\Models\WhatsappAutoReply;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating auto-reply rules...\n";

// Create some basic auto-reply rules
$autoReplies = [
    [
        'keyword' => 'hello',
        'reply_content' => 'Hello! Welcome to FreeDoctor. How can I help you today?',
        'reply_type' => 'text',
        'status' => 'active'
    ],
    [
        'keyword' => 'hi',
        'reply_content' => 'Hi there! Thanks for reaching out. How can I assist you?',
        'reply_type' => 'text', 
        'status' => 'active'
    ],
    [
        'keyword' => 'help',
        'reply_content' => 'I\'m here to help! You can ask me about our medical services, book appointments, or get health information.',
        'reply_type' => 'text',
        'status' => 'active'
    ],
    [
        'keyword' => 'appointment',
        'reply_content' => 'To book an appointment, please provide your preferred date and time. Our team will confirm availability.',
        'reply_type' => 'text',
        'status' => 'active'
    ]
];

foreach ($autoReplies as $reply) {
    $existing = WhatsappAutoReply::where('keyword', $reply['keyword'])->first();
    if (!$existing) {
        WhatsappAutoReply::create($reply);
        echo "Created auto-reply for keyword: " . $reply['keyword'] . "\n";
    } else {
        echo "Auto-reply already exists for keyword: " . $reply['keyword'] . "\n";
    }
}

echo "\nAuto-reply rules created successfully!\n";
echo "Total auto-replies: " . WhatsappAutoReply::count() . "\n";
