<?php

// Add this route to test conversation data
use App\Models\WhatsappConversation;

Route::get('/test-conversations', function() {
    // Clear existing test data
    WhatsappConversation::where('phone', 'like', '+test%')->delete();
    
    // Create sample conversations
    $conversations = [
        [
            'phone' => '+test1234567890',
            'message' => 'Hello, I need help with appointment booking',
            'reply' => 'Hi! I can help you book an appointment. What type of consultation do you need?',
            'reply_type' => 'auto',
            'sent_at' => now()->subHours(2),
            'is_responded' => true,
            'lead_status' => 'active'
        ],
        [
            'phone' => '+test9876543210',
            'message' => 'What are your clinic timings?',
            'reply' => 'Our clinic is open Monday to Friday 9 AM to 6 PM, Saturday 9 AM to 2 PM.',
            'reply_type' => 'auto',
            'sent_at' => now()->subHours(5),
            'is_responded' => true,
            'lead_status' => 'active'
        ],
        [
            'phone' => '+test1122334455',
            'message' => 'I want to cancel my appointment',
            'reply' => null,
            'reply_type' => null,
            'sent_at' => null,
            'is_responded' => false,
            'lead_status' => 'pending'
        ],
        [
            'phone' => '+test5566778899',
            'message' => null,
            'reply' => 'Welcome to FreeDoctor! How can I assist you today?',
            'reply_type' => 'auto',
            'sent_at' => now()->subMinutes(30),
            'is_responded' => false,
            'lead_status' => 'new'
        ]
    ];
    
    foreach ($conversations as $data) {
        WhatsappConversation::create($data);
    }
    
    return response()->json([
        'message' => 'Sample conversations created successfully!',
        'total_conversations' => WhatsappConversation::count(),
        'test_conversations' => WhatsappConversation::where('phone', 'like', '+test%')->count()
    ]);
});
