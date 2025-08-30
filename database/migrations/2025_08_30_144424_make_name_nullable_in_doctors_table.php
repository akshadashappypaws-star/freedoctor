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
        Schema::table('doctors', function (Blueprint $table) {
            // Make the name column nullable since we're using doctor_name instead
            $table->string('name')->nullable()->change();
        });
        
        // Copy data from doctor_name to name for existing records where name is empty
        DB::statement('UPDATE doctors SET name = doctor_name WHERE name IS NULL OR name = ""');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Revert name column back to NOT NULL (but this might fail if there are NULL values)
            $table->string('name')->nullable(false)->change();
        });
    }
};
