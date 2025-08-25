<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking WhatsApp Tables Structure ===\n\n";

// Check whatsapp_conversations table
echo "whatsapp_conversations columns:\n";
if (Schema::hasTable('whatsapp_conversations')) {
    $columns = Schema::getColumnListing('whatsapp_conversations');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
} else {
    echo "Table does not exist\n";
}

echo "\n";

// Check whatsapp_messages table
echo "whatsapp_messages columns:\n";
if (Schema::hasTable('whatsapp_messages')) {
    $columns = Schema::getColumnListing('whatsapp_messages');
    foreach ($columns as $column) {
        echo "- $column\n";
    }
} else {
    echo "Table does not exist\n";
}

echo "\n";

// Check if automation tables exist
$automationTables = [
    'whatsapp_ai_analysis',
    'whatsapp_user_behavior', 
    'whatsapp_automation_rules',
    'whatsapp_system_health',
    'whatsapp_weekly_reports'
];

foreach ($automationTables as $table) {
    echo "$table: " . (Schema::hasTable($table) ? 'EXISTS' : 'NOT EXISTS') . "\n";
}

echo "\n=== Check Complete ===\n";
