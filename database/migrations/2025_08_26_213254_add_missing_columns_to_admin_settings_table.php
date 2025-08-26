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
        Schema::table('admin_settings', function (Blueprint $table) {
            // Add missing columns that the AdminSetting model expects
            $table->string('setting_key')->nullable()->after('id');
            $table->string('setting_name')->nullable()->after('setting_key');
            $table->decimal('percentage_value', 8, 2)->nullable()->after('setting_name');
            $table->decimal('amount', 10, 2)->nullable()->after('percentage_value');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropColumn(['setting_key', 'setting_name', 'percentage_value', 'amount', 'is_active']);
        });
    }
};
