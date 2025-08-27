<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns exist before trying to rename them
        $columns = Schema::getColumnListing('whatsapp_system_healths');
        
        if (in_array('component', $columns) && !in_array('component_name', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE component component_name VARCHAR(255)');
        }
        
        if (in_array('metrics', $columns) && !in_array('performance_metrics', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE metrics performance_metrics JSON');
        }
        
        if (in_array('error_message', $columns) && !in_array('last_error', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE error_message last_error TEXT');
        }
        
        if (in_array('checked_at', $columns) && !in_array('last_check_at', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE checked_at last_check_at TIMESTAMP');
        }
        
        // Create a view for the singular table name that the model expects
        $viewExists = DB::select("SHOW TABLES LIKE 'whatsapp_system_health'");
        if (empty($viewExists)) {
            DB::statement('CREATE VIEW whatsapp_system_health AS SELECT * FROM whatsapp_system_healths');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the view first
        DB::statement('DROP VIEW IF EXISTS whatsapp_system_health');
        
        // Rename columns back using raw SQL
        DB::statement('ALTER TABLE whatsapp_system_healths CHANGE component_name component VARCHAR(255)');
        DB::statement('ALTER TABLE whatsapp_system_healths CHANGE performance_metrics metrics JSON');
        DB::statement('ALTER TABLE whatsapp_system_healths CHANGE last_error error_message TEXT');
        DB::statement('ALTER TABLE whatsapp_system_healths CHANGE last_check_at checked_at TIMESTAMP');
    }
};
