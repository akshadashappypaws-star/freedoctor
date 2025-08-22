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
        // First, change the column to VARCHAR to allow the update
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN camp_type VARCHAR(50) NOT NULL DEFAULT 'medical'");
        
        // Update existing data
        DB::table('campaigns')->where('camp_type', 'free')->update(['camp_type' => 'medical']);
        DB::table('campaigns')->where('camp_type', 'paid')->update(['camp_type' => 'surgical']);
        
        // Now change it back to ENUM with the new values
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN camp_type ENUM('medical', 'surgical') NOT NULL DEFAULT 'medical'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change to VARCHAR to allow update
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN camp_type VARCHAR(50) NOT NULL DEFAULT 'free'");
        
        // Revert data
        DB::table('campaigns')->where('camp_type', 'medical')->update(['camp_type' => 'free']);
        DB::table('campaigns')->where('camp_type', 'surgical')->update(['camp_type' => 'paid']);
        
        // Revert the enum definition
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN camp_type ENUM('free', 'paid') NOT NULL DEFAULT 'free'");
    }
};
