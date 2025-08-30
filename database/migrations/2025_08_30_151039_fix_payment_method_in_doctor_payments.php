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
            // Make payment_method nullable with a default value of 'razorpay'
            $table->string('payment_method')->nullable()->default('razorpay')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_payments', function (Blueprint $table) {
            // Revert payment_method back to NOT NULL (but this might fail if there are NULL values)
            $table->string('payment_method')->nullable(false)->change();
        });
    }
};
