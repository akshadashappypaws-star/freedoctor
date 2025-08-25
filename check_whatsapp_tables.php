<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking Existing WhatsApp Tables ===\n\n";

// List of WhatsApp related tables to check
$whatsappTables = [
    'whatsapp_conversations',
    'whatsapp_messages',
    'whatsapp_auto_replies',
    'whatsapp_templates',
    'whatsapp_bulk_messages',
    'whatsapp_chatgpt_prompts',
    'whatsapp_media',
    'whatsapp_lead_scores',
    'whatsapp_template_campaigns',
    'whatsapp_conversations_automation',
    'whatsapp_messages_automation',
    'whatsapp_ai_analysis',
    'whatsapp_user_behavior',
    'whatsapp_automation_rules',
    'whatsapp_workflows',
    'whatsapp_system_health',
    'whatsapp_weekly_reports',
    'whatsapp_template_stats',
    'whatsapp_keyword_tracking'
];

foreach ($whatsappTables as $table) {
    echo "Table: $table\n";
    if (Schema::hasTable($table)) {
        echo "  Status: EXISTS\n";
        $columns = Schema::getColumnListing($table);
        echo "  Columns: " . count($columns) . " (" . implode(', ', $columns) . ")\n";
        
        // Get row count
        try {
            $count = DB::table($table)->count();
            echo "  Records: $count\n";
        } catch (Exception $e) {
            echo "  Records: Error counting - " . $e->getMessage() . "\n";
        }
    } else {
        echo "  Status: MISSING\n";
    }
    echo "\n";
}

echo "=== Migration Status Check ===\n";
try {
    $migrations = DB::table('migrations')
        ->where('migration', 'like', '%whatsapp%')
        ->orderBy('migration')
        ->get();
    
    foreach ($migrations as $migration) {
        echo "✅ " . $migration->migration . " (batch: " . $migration->batch . ")\n";
    }
} catch (Exception $e) {
    echo "Error checking migrations: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
