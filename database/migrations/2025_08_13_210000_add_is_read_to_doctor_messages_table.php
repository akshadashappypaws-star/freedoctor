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
        Schema::table('doctor_messages', function (Blueprint $table) {
            // Add is_read column if it doesn't exist
            if (!Schema::hasColumn('doctor_messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_messages', function (Blueprint $table) {
            if (Schema::hasColumn('doctor_messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });
    }
};
