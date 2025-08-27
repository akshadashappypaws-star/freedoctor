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
        Schema::create('whatsapp_patient_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->index();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->json('medical_history')->nullable();
            $table->json('allergies')->nullable();
            $table->json('current_medications')->nullable();
            $table->enum('registration_step', ['phone_verification', 'basic_info', 'medical_info', 'completed'])->default('phone_verification');
            $table->boolean('is_completed')->default(false);
            $table->json('form_data')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('registration_completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_patient_registrations');
    }
};
