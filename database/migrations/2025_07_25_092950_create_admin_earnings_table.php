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
        Schema::create('admin_earnings', function (Blueprint $table) {
            $table->id();
            $table->enum('earning_type', ['registration', 'sponsor', 'doctor_registration']);
            $table->string('reference_type'); // PatientPayment, CampaignSponsor, DoctorPayment
            $table->unsignedBigInteger('reference_id');
            $table->decimal('original_amount', 10, 2);
            $table->decimal('percentage_rate', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('net_amount_to_receiver', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_earnings');
    }
};
