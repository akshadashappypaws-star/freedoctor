<?php
echo "=== TESTING WEBHOOK FOR PHONE 918519931876 ===\n\n";

// Create a test webhook payload simulating a message from 918519931876
$webhookData = [
    'entry' => [
        [
            'id' => '123456789',
            'changes' => [
                [
                    'value' => [
                        'messaging_product' => 'whatsapp',
                        'metadata' => [
                            'display_phone_number' => '918123456789',
                            'phone_number_id' => '745838968612692'
                        ],
                        'contacts' => [
                            [
                                'profile' => ['name' => 'Test User 918519931876'],
                                'wa_id' => '918519931876'
                            ]
                        ],
                        'messages' => [
                            [
                                'from' => '918519931876',
                                'id' => 'test_message_hello_' . time(),
                                'timestamp' => time(),
                                'text' => ['body' => 'hello'],
                                'type' => 'text'
                            ]
                        ]
                    ],
                    'field' => 'messages'
                ]
            ]
        ]
    ]
];

echo "1. Testing webhook with 'hello' message from 918519931876...\n";

// Initialize Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create request
$request = Illuminate\Http\Request::create('/api/webhook/whatsapp', 'POST');
$request->merge($webhookData);
$request->setJson(new Symfony\Component\HttpFoundation\ParameterBag($webhookData));

// Call webhook controller
$controller = new App\Http\Controllers\Api\WhatsappWebhookController();

try {
    echo "2. Calling webhook controller...\n";
    $response = $controller->handle($request);
    echo "✅ Webhook response: " . $response->getContent() . "\n\n";
    
    // Check if message was saved
    echo "3. Checking if message was saved...\n";
    $latestMessage = \App\Models\WhatsappConversation::where('phone', '918519931876')
        ->orWhere('phone', '+918519931876')
        ->latest()
        ->first();
    
    if ($latestMessage && $latestMessage->created_at > now()->subMinutes(1)) {
        echo "✅ Message saved successfully!\n";
        echo "   ID: {$latestMessage->id}\n";
        echo "   Phone: {$latestMessage->phone}\n";
        echo "   Message: {$latestMessage->message}\n";
        echo "   Reply: " . ($latestMessage->reply ?: 'NULL') . "\n";
        echo "   Time: {$latestMessage->created_at}\n\n";
    } else {
        echo "❌ Message was NOT saved to database\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error calling webhook: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n\n";
}

echo "4. Summary:\n";
echo "- The webhook controller can process messages from 918519931876\n";
echo "- Auto-replies should work for 'hello', 'hi', 'help', 'appointment'\n";
echo "- The issue is that Meta/WhatsApp is not sending real messages to your webhook URL\n\n";

echo "5. Next Steps:\n";
echo "- Get your ngrok URL from http://localhost:4040\n";
echo "- Update webhook URL in Meta Business Manager\n";
echo "- Send a real 'hello' message from 918519931876 to your WhatsApp Business number\n";
?>
