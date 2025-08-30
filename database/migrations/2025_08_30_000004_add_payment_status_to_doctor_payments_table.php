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
        Schema::table('doctor_payments', function (Blueprint $table) {
            // Add payment_status column
            $table->string('payment_status')->default('pending')->after('status');
        });

        // Copy existing status values to payment_status column
        DB::statement("UPDATE doctor_payments SET payment_status = status WHERE payment_status IS NULL OR payment_status = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_payments', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
