<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WhatsappConversation;
use App\Http\Controllers\Admin\WhatsappConversationController;
use Illuminate\Http\Request;

echo "Testing WhatsApp Conversation Controller...\n";

// Clear existing test data
WhatsappConversation::where('phone', 'like', '+test%')->delete();

// Create test conversation
$testConversation = WhatsappConversation::create([
    'phone' => '+test1234567890',
    'message' => 'Hello, this is a test message',
    'reply' => 'Thank you for your message! How can I help you?',
    'reply_type' => 'auto',
    'sent_at' => now(),
    'is_responded' => true,
    'lead_status' => 'active'
]);

echo "Created test conversation: " . $testConversation->phone . "\n";

// Test the controller
try {
    $request = new Request();
    $controller = new WhatsappConversationController(app(App\Services\WhatsAppService::class));
    
    echo "Testing controller index method...\n";
    
    // This will test if the controller can process the data correctly
    $result = $controller->index($request);
    
    echo "Controller test completed successfully!\n";
    echo "Check the conversations page now.\n";
    
} catch (Exception $e) {
    echo "Controller test failed: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\nConversation count: " . WhatsappConversation::count() . "\n";

?>
