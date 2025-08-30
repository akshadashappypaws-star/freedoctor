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
        Schema::table('admin_messages', function (Blueprint $table) {
            // Add read column as boolean, default to false (unread)
            $table->boolean('read')->default(false)->after('status');
            
            // Add index for better query performance on read status
            $table->index('read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            // Drop index first
            $table->dropIndex(['read']);
            
            // Drop the column
            $table->dropColumn('read');
        });
    }
};
