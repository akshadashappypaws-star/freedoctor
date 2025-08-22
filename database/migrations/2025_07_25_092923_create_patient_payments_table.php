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
        Schema::create('patient_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_registration_id')->constrained('patient_registrations')->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_id')->nullable();
            $table->string('order_id')->unique();
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('payment_details')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->decimal('admin_commission', 10, 2)->default(0.00);
            $table->decimal('doctor_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_payments');
    }
};
