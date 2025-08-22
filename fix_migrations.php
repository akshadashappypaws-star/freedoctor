<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Schema;

// Laravel Application Bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Get the database connection
    $db = app('db');
    
    // List of migrations that already have their changes applied but aren't marked as run
    $migrationsToCheck = [
        '2025_07_31_074818_add_amount_to_admin_settings_table' => ['table' => 'admin_settings', 'column' => 'amount'],
        '2025_07_31_084428_add_percentage_value_to_admin_settings_table' => ['table' => 'admin_settings', 'column' => 'percentage_value'],
        '2025_07_31_195911_create_doctor_messages_table' => ['table' => 'doctor_messages', 'type' => 'table'],
        '2025_08_05_174103_create_categories_table' => ['table' => 'categories', 'type' => 'table'],
        '2025_08_05_174219_add_category_id_to_campaigns_table' => ['table' => 'campaigns', 'column' => 'category_id'],
        '2025_08_05_174827_modify_categories_table' => ['table' => 'categories', 'type' => 'modify'],
        '2025_08_05_213816_add_coordinates_to_campaigns_table' => ['table' => 'campaigns', 'column' => 'latitude'],
        '2025_08_08_000001_fix_user_messages_id_column' => ['table' => 'user_messages', 'type' => 'modify'],
        '2025_08_12_194807_create_organiclead_table' => ['table' => 'organiclead', 'type' => 'table'],
    ];
    
    // Check current max batch number
    $maxBatch = $db->table('migrations')->max('batch') ?? 0;
    $newBatch = $maxBatch + 1;
    
    echo "Current max batch: {$maxBatch}\n";
    echo "New batch will be: {$newBatch}\n\n";
    
    foreach ($migrationsToCheck as $migration => $check) {
        // Check if migration is already recorded
        $exists = $db->table('migrations')->where('migration', $migration)->exists();
        
        if (!$exists) {
            // Check if the changes are already applied
            $shouldMark = false;
            
            if (isset($check['column'])) {
                // Check if column exists
                $shouldMark = Schema::hasColumn($check['table'], $check['column']);
                echo "Checking column {$check['table']}.{$check['column']}: " . ($shouldMark ? 'EXISTS' : 'NOT EXISTS') . "\n";
            } elseif (isset($check['type']) && $check['type'] === 'table') {
                // Check if table exists
                $shouldMark = Schema::hasTable($check['table']);
                echo "Checking table {$check['table']}: " . ($shouldMark ? 'EXISTS' : 'NOT EXISTS') . "\n";
            } else {
                // For modify operations, assume they need to be marked as complete
                $shouldMark = true;
                echo "Marking modify operation as complete: {$migration}\n";
            }
            
            if ($shouldMark) {
                $db->table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $newBatch
                ]);
                echo "✓ Marked migration as completed: {$migration}\n";
            } else {
                echo "- Migration needs to run: {$migration}\n";
            }
        } else {
            echo "- Migration already marked as completed: {$migration}\n";
        }
        echo "\n";
    }
    
    echo "Done! You can now run php artisan migrate to continue with remaining migrations.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
