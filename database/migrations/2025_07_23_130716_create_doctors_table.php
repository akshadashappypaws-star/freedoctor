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
        Schema::create('doctors', function (Blueprint $table) {
    $table->id();
    $table->string('doctor_name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->boolean('phone_verified')->default(false);
    $table->string('location')->nullable();
    $table->string('gender')->nullable();
    $table->unsignedBigInteger('specialty_id')->nullable(); // For department/specialty relation
    $table->string('hospital_name')->nullable();             // Changed from hospital_id to hospital_name
    $table->integer('experience')->nullable();               // in years
    $table->text('description')->nullable();                 // Bio or about doctor
    $table->string('intro_video')->nullable();               // New: Intro video link or filename
    $table->string('image')->nullable();                     // Profile image
    $table->string('password');
    $table->timestamp('email_verified_at')->nullable();
    $table->boolean('status')->default(1);                   // Active/inactive
    $table->rememberToken();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
