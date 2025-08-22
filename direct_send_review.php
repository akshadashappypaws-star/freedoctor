<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WhatsappCloudApiService;
use App\Models\WhatsappTemplate;

echo "=== Direct Template Send Test ===\n";

try {
    // Get the review_message template
    $template = WhatsappTemplate::where('name', 'review_message')->first();
    
    if (!$template) {
        echo "❌ review_message template not found\n";
        exit(1);
    }
    
    echo "✅ Template found: {$template->name}\n";
    echo "Status: {$template->status}\n";
    echo "Language: {$template->language}\n";
    echo "Category: {$template->category}\n\n";
    
    // Use the WhatsApp service directly
    $whatsappService = new WhatsappCloudApiService();
    
    echo "Sending review_message template to +918519931876...\n";
    
    // Send with timeout handling
    set_time_limit(30); // 30 second timeout
    
    $result = $whatsappService->sendTemplate('+918519931876', 'review_message');
    
    echo "Send operation completed.\n";
    
    if ($result && isset($result['success']) && $result['success']) {
        echo "✅ SUCCESS! Message sent\n";
        echo "Message ID: {$result['message_id']}\n";
    } else {
        echo "❌ Send failed or returned false\n";
        echo "Result: " . print_r($result, true) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    
    // Check if it's a timeout issue
    if (strpos($e->getMessage(), 'timeout') !== false || 
        strpos($e->getMessage(), 'timed out') !== false) {
        echo "⚠️  This appears to be a timeout issue.\n";
        echo "The message may still be sent even though we got a timeout.\n";
        echo "Please check your WhatsApp to see if the message arrived.\n";
    }
}

echo "\n=== Check Your WhatsApp Now! ===\n";
echo "If you received the review_message, the system is working.\n";
echo "If not, there may be an API connectivity issue.\n";
?>
