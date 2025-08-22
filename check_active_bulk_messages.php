<?php

require_once 'vendor/autoload.php';

use App\Models\WhatsappBulkMessage;
use App\Models\WhatsappTemplate;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

echo "Checking Active Bulk Messages\n";
echo "=============================\n\n";

// Find the doctor_flow_lead template
$template = WhatsappTemplate::where('name', 'doctor_flow_lead')->first();

if ($template) {
    echo "Template ID: " . $template->id . "\n";
    echo "Template Name: " . $template->name . "\n\n";
    
    // Check for bulk messages using this template
    $bulkMessages = WhatsappBulkMessage::where('template_id', $template->id)
        ->whereIn('status', ['pending', 'processing'])
        ->get();
    
    echo "Active bulk messages using this template:\n";
    if ($bulkMessages->count() > 0) {
        foreach ($bulkMessages as $message) {
            echo "- ID: {$message->id}, Status: {$message->status}, Created: {$message->created_at}\n";
            echo "  Recipients: " . (json_decode($message->recipients) ? count(json_decode($message->recipients)) : 0) . "\n";
            echo "  Parameters: " . $message->parameters . "\n\n";
        }
    } else {
        echo "No active bulk messages found.\n";
    }
    
    // Check recent bulk messages
    $recentMessages = WhatsappBulkMessage::where('template_id', $template->id)
        ->where('created_at', '>=', now()->subHour())
        ->get();
        
    echo "Recent bulk messages (last hour):\n";
    if ($recentMessages->count() > 0) {
        foreach ($recentMessages as $message) {
            echo "- ID: {$message->id}, Status: {$message->status}, Created: {$message->created_at}\n";
            echo "  Parameters: " . $message->parameters . "\n";
        }
    } else {
        echo "No recent bulk messages found.\n";
    }
} else {
    echo "Template 'doctor_flow_lead' not found!\n";
}

echo "\nCheck completed.\n";
