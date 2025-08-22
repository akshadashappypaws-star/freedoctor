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
        Schema::create('doctor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('registration'); // registration, withdrawal, earning, etc.
            $table->decimal('amount', 10, 2)->default(500.00);
            $table->string('payment_id')->nullable(); // Razorpay payment ID
            $table->string('order_id')->nullable(); // Razorpay order ID
            $table->string('payment_status')->default('pending'); // pending, success, failed, processing
            $table->string('razorpay_payout_id')->nullable(); // For withdrawals
            $table->json('payment_details')->nullable(); // Store Razorpay response
            $table->text('bank_details')->nullable(); // JSON stored bank details for withdrawals
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('receipt_number')->nullable();
            $table->text('description')->default('Doctor Registration Fee');
            $table->text('failure_reason')->nullable();
            $table->timestamps();
            
            $table->index(['doctor_id', 'type']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_payments');
    }
};
