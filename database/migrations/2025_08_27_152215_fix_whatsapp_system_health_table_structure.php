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
        // Check and add only missing columns
        if (!Schema::hasColumn('whatsapp_system_healths', 'health_percentage')) {
            Schema::table('whatsapp_system_healths', function (Blueprint $table) {
                $table->decimal('health_percentage', 5, 2)->default(100.00)->after('status');
            });
        }
        
        if (!Schema::hasColumn('whatsapp_system_healths', 'requests_today')) {
            Schema::table('whatsapp_system_healths', function (Blueprint $table) {
                $table->integer('requests_today')->default(0)->after('health_percentage');
            });
        }
        
        if (!Schema::hasColumn('whatsapp_system_healths', 'avg_response_time')) {
            Schema::table('whatsapp_system_healths', function (Blueprint $table) {
                $table->decimal('avg_response_time', 8, 3)->default(0.000)->after('requests_today');
            });
        }
        
        if (!Schema::hasColumn('whatsapp_system_healths', 'success_rate')) {
            Schema::table('whatsapp_system_healths', function (Blueprint $table) {
                $table->decimal('success_rate', 5, 2)->default(100.00)->after('avg_response_time');
            });
        }
        
        // Rename columns using raw SQL (only if they haven't been renamed already)
        $columns = Schema::getColumnListing('whatsapp_system_healths');
        
        if (in_array('component', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE component component_name VARCHAR(255)');
        }
        
        if (in_array('metrics', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE metrics performance_metrics JSON');
        }
        
        if (in_array('error_message', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE error_message last_error TEXT');
        }
        
        if (in_array('checked_at', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE checked_at last_check_at TIMESTAMP');
        }
        
        // Create view only if it doesn't exist
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
        $columns = Schema::getColumnListing('whatsapp_system_healths');
        
        if (in_array('component_name', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE component_name component VARCHAR(255)');
        }
        
        if (in_array('performance_metrics', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE performance_metrics metrics JSON');
        }
        
        if (in_array('last_error', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE last_error error_message TEXT');
        }
        
        if (in_array('last_check_at', $columns)) {
            DB::statement('ALTER TABLE whatsapp_system_healths CHANGE last_check_at checked_at TIMESTAMP');
        }
        
        Schema::table('whatsapp_system_healths', function (Blueprint $table) {
            // Drop the added columns
            if (Schema::hasColumn('whatsapp_system_healths', 'health_percentage')) {
                $table->dropColumn('health_percentage');
            }
            if (Schema::hasColumn('whatsapp_system_healths', 'requests_today')) {
                $table->dropColumn('requests_today');
            }
            if (Schema::hasColumn('whatsapp_system_healths', 'avg_response_time')) {
                $table->dropColumn('avg_response_time');
            }
            if (Schema::hasColumn('whatsapp_system_healths', 'success_rate')) {
                $table->dropColumn('success_rate');
            }
        });
    }
};
