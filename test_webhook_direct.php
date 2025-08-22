<?php
require_once 'vendor/autoload.php';

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing webhook with simulated WhatsApp message...\n";

// Simulate a WhatsApp message webhook payload
$testPayload = [
    'entry' => [
        [
            'changes' => [
                [
                    'value' => [
                        'messages' => [
                            [
                                'from' => '919876543210', // Your phone number here
                                'id' => 'test_message_' . time(),
                                'text' => [
                                    'body' => 'Hello from test webhook! ' . time()
                                ],
                                'timestamp' => time()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

echo "Test payload: " . json_encode($testPayload, JSON_PRETTY_PRINT) . "\n\n";

// Create a request to the webhook controller
$request = \Illuminate\Http\Request::create('/api/webhook/whatsapp', 'POST');
$request->headers->set('Content-Type', 'application/json');

// Set the JSON data in the request
$request->setJson(new \Symfony\Component\HttpFoundation\ParameterBag($testPayload));

// Also set in request data
$request->merge($testPayload);

echo "Request method: " . $request->method() . "\n";
echo "Request path: " . $request->path() . "\n";
echo "Request data: " . json_encode($request->all()) . "\n\n";

// Call the webhook controller directly
$controller = new \App\Http\Controllers\Api\WhatsappWebhookController();
$response = $controller->handle($request);

echo "Webhook response: " . $response->getContent() . "\n";
echo "Status code: " . $response->getStatusCode() . "\n";

// Check if message was saved
$latestMessage = \App\Models\WhatsappConversation::latest()->first();
if ($latestMessage) {
    echo "\nLatest message in database:\n";
    echo "Phone: " . $latestMessage->phone . "\n";
    echo "Message: " . $latestMessage->message . "\n";
    echo "Created: " . $latestMessage->created_at . "\n";
} else {
    echo "\nNo messages found in database.\n";
}

echo "\nTotal messages in database: " . \App\Models\WhatsappConversation::count() . "\n";
