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
        Schema::table('doctor_withdrawals', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('status')->comment('Razorpay order ID for tracking');
            $table->string('payment_id')->nullable()->after('order_id')->comment('Razorpay payment/refund ID');
            $table->json('payment_details')->nullable()->after('payment_id')->comment('Additional payment/refund details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_withdrawals', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_id', 'payment_details']);
        });
    }
};
