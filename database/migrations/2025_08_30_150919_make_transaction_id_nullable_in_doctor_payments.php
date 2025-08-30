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
        Schema::table('doctor_payments', function (Blueprint $table) {
            // Make transaction_id nullable to avoid "doesn't have a default value" errors
            $table->string('transaction_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_payments', function (Blueprint $table) {
            // Revert transaction_id back to NOT NULL (but this might fail if there are NULL values)
            $table->string('transaction_id')->nullable(false)->change();
        });
    }
};
