<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WhatsappCloudApiService;
use App\Models\WhatsappTemplate;

echo "=== Sending review_message with Parameters ===\n";

try {
    $template = WhatsappTemplate::where('name', 'review_message')->first();
    
    if (!$template) {
        echo "❌ review_message template not found\n";
        exit(1);
    }
    
    echo "✅ Template: {$template->name}\n";
    echo "Parameter needed: {{1}} = experience type\n\n";
    
    // Initialize WhatsApp service
    $whatsappService = new WhatsappCloudApiService();
    
    // Prepare template components with parameter data
    $templateParams = [
        'consultation' // This will replace {{1}} in the template
    ];
    
    echo "Sending review_message to +918519931876...\n";
    echo "Parameter value: 'consultation'\n";
    echo "Final message will say: 'your recent consultation experience'\n\n";
    
    // Use the sendMessage method which handles template parameters
    $result = $whatsappService->sendMessage(
        '+918519931876', 
        null, // message text (not used for templates)
        'review_message', // template name
        $templateParams // parameters array
    );
    
    if ($result && isset($result['success']) && $result['success']) {
        echo "✅ SUCCESS! Review message sent with parameters\n";
        echo "Message ID: {$result['message_id']}\n";
        echo "Check your WhatsApp for the survey message!\n";
    } else {
        echo "❌ FAILED to send message\n";
        echo "Result: " . print_r($result, true) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Template Parameter Rules ===\n";
echo "✅ Templates without {{}} placeholders can be sent as-is\n";
echo "❌ Templates with {{1}}, {{2}}, etc. need parameter data\n";
echo "📝 Always check template content before sending\n";
?>
