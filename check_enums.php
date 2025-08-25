<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking reply_type column definition ===\n\n";

// Get column info for whatsapp_conversations table
$columns = DB::select("DESCRIBE whatsapp_conversations");

foreach ($columns as $column) {
    if ($column->Field === 'reply_type') {
        echo "Column: " . $column->Field . "\n";
        echo "Type: " . $column->Type . "\n";
        echo "Null: " . $column->Null . "\n";
        echo "Default: " . $column->Default . "\n";
        echo "Extra: " . $column->Extra . "\n\n";
        
        // If it's an enum, let's see the values
        if (strpos($column->Type, 'enum') !== false) {
            echo "ENUM values detected: " . $column->Type . "\n";
        }
    }
}

// Also check other enum columns
echo "=== All ENUM columns in whatsapp_conversations ===\n";
foreach ($columns as $column) {
    if (strpos($column->Type, 'enum') !== false) {
        echo $column->Field . ": " . $column->Type . "\n";
    }
}

echo "\n=== Check Complete ===\n";
