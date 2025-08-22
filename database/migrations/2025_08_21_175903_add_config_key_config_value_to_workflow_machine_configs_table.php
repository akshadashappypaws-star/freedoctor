<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workflow_machine_configs', function (Blueprint $table) {
            // Add config_key and config_value columns for compatibility
            $table->string('config_key')->nullable()->after('config_name');
            $table->text('config_value')->nullable()->after('config_json');
            
            // Add indexes for the new columns
            $table->index(['machine_type', 'config_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_machine_configs', function (Blueprint $table) {
            $table->dropIndex(['machine_type', 'config_key']);
            $table->dropColumn(['config_key', 'config_value']);
        });
    }
};
