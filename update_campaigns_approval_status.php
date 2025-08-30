<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Updating existing campaigns with default approval_status...\n";
    
    // Find campaigns without approval_status or with null values
    $campaignsToUpdate = \App\Models\Campaign::whereNull('approval_status')->count();
    echo "Found $campaignsToUpdate campaigns without approval_status\n";
    
    if ($campaignsToUpdate > 0) {
        // Update campaigns without approval_status to 'pending'
        $updated = \App\Models\Campaign::whereNull('approval_status')
                                      ->update(['approval_status' => 'pending']);
        echo "Updated $updated campaigns to 'pending' status\n";
    }
    
    echo "✅ All campaigns now have approval_status set.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
