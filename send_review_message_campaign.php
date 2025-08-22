<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappBulkMessage;
use App\Models\WhatsappTemplate;
use App\Jobs\SendBulkWhatsappMessage;

echo "=== Creating Bulk Message Campaign for review_message ===\n";

try {
    // Get the review_message template
    $template = WhatsappTemplate::where('name', 'review_message')->first();
    
    if (!$template) {
        echo "❌ review_message template not found\n";
        exit(1);
    }
    
    echo "✅ Template found: {$template->name} (ID: {$template->id})\n";
    
    // Create a bulk message campaign
    $bulkMessage = WhatsappBulkMessage::create([
        'template_id' => $template->id,
        'recipients' => json_encode(['+918519931876']),
        'parameters' => null,
        'is_scheduled' => false,
        'scheduled_at' => null,
        'status' => 'pending',
        'total_recipients' => 1,
        'sent_count' => 0,
        'failed_count' => 0,
        'target_category' => 'test'
    ]);
    
    echo "✅ Bulk message campaign created (ID: {$bulkMessage->id})\n";
    
    // Dispatch the job immediately
    echo "Dispatching WhatsApp send job...\n";
    SendBulkWhatsappMessage::dispatch($bulkMessage->id);
    
    echo "✅ Job dispatched! Message should be sent shortly.\n";
    echo "Campaign ID: {$bulkMessage->id}\n";
    echo "Check your WhatsApp for the review_message!\n";
    
    // Show the template content for reference
    echo "\nTemplate content preview:\n";
    echo "Category: {$template->category}\n";
    echo "Content: " . substr($template->content, 0, 200) . "...\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Campaign Created ===\n";
?>
