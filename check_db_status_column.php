<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $columns = DB::select("DESCRIBE whatsapp_bulk_messages");
    
    echo "WhatsApp Bulk Messages Table Structure:\n";
    foreach ($columns as $column) {
        if ($column->Field === 'status') {
            echo "Status column: Type={$column->Type}, Null={$column->Null}, Default={$column->Default}\n";
        }
    }
    
    // Check if there are any records with long status values
    $longStatuses = DB::select("SELECT status, LENGTH(status) as length FROM whatsapp_bulk_messages WHERE LENGTH(status) > 10");
    
    if (!empty($longStatuses)) {
        echo "\nRecords with long status values:\n";
        foreach ($longStatuses as $record) {
            echo "Status: '{$record->status}', Length: {$record->length}\n";
        }
    } else {
        echo "\nNo records with abnormally long status values found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
