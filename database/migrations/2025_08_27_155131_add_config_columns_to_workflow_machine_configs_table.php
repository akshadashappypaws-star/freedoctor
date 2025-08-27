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
            $table->string('config_key')->after('id');
            $table->text('config_value')->after('config_key');
            $table->string('machine_type')->default('template')->after('config_value');
            $table->string('environment')->default('production')->after('machine_type');
            $table->boolean('is_active')->default(true)->after('environment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_machine_configs', function (Blueprint $table) {
            $table->dropColumn(['config_key', 'config_value', 'machine_type', 'environment', 'is_active']);
        });
    }
};
