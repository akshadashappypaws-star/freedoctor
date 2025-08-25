<?php

// Simple script to check if status column exists
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $columns = Schema::getColumnListing('whatsapp_conversations');
    echo "WhatsApp Conversations table columns:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }
    
    if (in_array('status', $columns)) {
        echo "\n✅ STATUS column exists!\n";
        
        // Test a simple query to make sure it works
        $count = DB::table('whatsapp_conversations')->where('status', 'active')->count();
        echo "✅ Query test passed! Found $count active conversations.\n";
    } else {
        echo "\n❌ STATUS column missing!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
