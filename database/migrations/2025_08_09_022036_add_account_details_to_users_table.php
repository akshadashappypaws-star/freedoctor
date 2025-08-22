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
        Schema::table('users', function (Blueprint $table) {
            // Razorpay account details for withdrawals
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('razorpay_contact_id')->nullable(); // Razorpay contact ID
            $table->string('razorpay_fund_account_id')->nullable(); // Razorpay fund account ID
            $table->decimal('total_earnings', 10, 2)->default(0); // Track total earnings
            $table->decimal('withdrawn_amount', 10, 2)->default(0); // Track withdrawn amount
            $table->decimal('available_balance', 10, 2)->default(0); // Available for withdrawal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bank_account_number',
                'bank_ifsc_code', 
                'bank_name',
                'account_holder_name',
                'razorpay_contact_id',
                'razorpay_fund_account_id',
                'total_earnings',
                'withdrawn_amount',
                'available_balance'
            ]);
        });
    }
};
