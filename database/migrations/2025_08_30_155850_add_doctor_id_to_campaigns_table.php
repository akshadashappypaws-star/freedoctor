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
        Schema::table('campaigns', function (Blueprint $table) {
            // Add doctor_id column as foreign key to doctors table
            $table->unsignedBigInteger('doctor_id')->nullable()->after('id');
            
            // Add foreign key constraint if doctors table exists
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            
            // Add index for better query performance
            $table->index('doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['doctor_id']);
            $table->dropIndex(['doctor_id']);
            
            // Drop the column
            $table->dropColumn('doctor_id');
        });
    }
};
