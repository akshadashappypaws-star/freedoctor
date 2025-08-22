<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Create test conversation
DB::table('whatsapp_conversations')->insert([
    'phone' => '+919876543210',
    'message' => 'Hello, I need a doctor consultation',
    'message_id' => 'wamid.TEST123456789',
    'reply' => 'Hello! I can help you find a doctor. What type of consultation do you need?',
    'reply_type' => 'text',
    'is_responded' => 1,
    'lead_status' => 'new',
    'sent_at' => now(),
    'created_at' => now(),
    'updated_at' => now()
]);

// Create another message in the same conversation
DB::table('whatsapp_conversations')->insert([
    'phone' => '+919876543210',
    'message' => 'I need a cardiologist',
    'message_id' => 'wamid.TEST123456790',
    'reply' => 'I found several cardiologists in your area. Let me show you the options.',
    'reply_type' => 'text',
    'is_responded' => 1,
    'lead_status' => 'interested',
    'sent_at' => now(),
    'created_at' => now(),
    'updated_at' => now()
]);

// Create a conversation from another phone number
DB::table('whatsapp_conversations')->insert([
    'phone' => '+919988776655',
    'message' => 'Hi, can you help me find a dentist?',
    'message_id' => 'wamid.TEST555666777',
    'reply' => 'Certainly! I can help you find a dentist. What location are you looking for?',
    'reply_type' => 'text',
    'is_responded' => 1,
    'lead_status' => 'new',
    'sent_at' => now(),
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Test conversations created successfully!\n";
echo "Test phone numbers: +919876543210, +919988776655\n";
echo "You can now test the conversation interface.\n";
