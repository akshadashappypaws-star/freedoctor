<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking whatsapp_user_behavior table structure ===\n\n";

$columns = DB::select("DESCRIBE whatsapp_user_behavior");

foreach ($columns as $column) {
    echo "Column: " . $column->Field . "\n";
    echo "Type: " . $column->Type . "\n";
    echo "Null: " . $column->Null . "\n";
    echo "Default: " . $column->Default . "\n\n";
}

echo "=== Check Complete ===\n";
