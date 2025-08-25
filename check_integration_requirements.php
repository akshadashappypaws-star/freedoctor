<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking Required Columns for Integration ===\n\n";

// Check if whatsapp_conversations needs any additional columns for automation
echo "whatsapp_conversations analysis:\n";
$conversationColumns = Schema::getColumnListing('whatsapp_conversations');
$requiredConversationColumns = [
    'phone' => 'exists',
    'message' => 'exists', 
    'reply' => 'exists',
    'sentiment' => 'exists',
    'is_processed' => 'exists',
    'ai_analysis_id' => 'missing', // Foreign key to ai_analysis
    'automation_rule_id' => 'missing', // Which rule was triggered
    'user_behavior_id' => 'missing' // Link to behavior tracking
];

foreach ($requiredConversationColumns as $column => $status) {
    $hasColumn = in_array($column, $conversationColumns);
    echo "  - $column: " . ($hasColumn ? "✅ EXISTS" : "❌ MISSING") . "\n";
}

echo "\nwhatsapp_lead_scores analysis:\n";
$leadColumns = Schema::getColumnListing('whatsapp_lead_scores');
$requiredLeadColumns = [
    'phone' => 'exists',
    'ai_analysis_id' => 'missing', // Link to AI analysis
    'automation_triggered' => 'missing', // Boolean for automation status
    'behavior_score' => 'missing' // Calculated from behavior data
];

foreach ($requiredLeadColumns as $column => $status) {
    $hasColumn = in_array($column, $leadColumns);
    echo "  - $column: " . ($hasColumn ? "✅ EXISTS" : "❌ MISSING") . "\n";
}

echo "\nRequired missing tables:\n";
$requiredTables = [
    'whatsapp_messages' => 'For storing individual messages',
    'whatsapp_automation_logs' => 'For logging automation executions'
];

foreach ($requiredTables as $table => $purpose) {
    $exists = Schema::hasTable($table);
    echo "  - $table: " . ($exists ? "✅ EXISTS" : "❌ MISSING") . " ($purpose)\n";
}

echo "\n=== Integration Requirements ===\n";
echo "1. Need to add foreign key columns to existing tables\n";
echo "2. Need to create whatsapp_messages table for message tracking\n";
echo "3. Need automation_logs table for execution tracking\n";

echo "\n=== Complete ===\n";
