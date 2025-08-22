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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('timings');
            $table->enum('camp_type', ['free', 'paid']);
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedBigInteger('doctor_id');
            $table->json('specializations');
            $table->string('contact_number');
            $table->integer('expected_patients');
            $table->json('images');
            $table->string('video')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('service_in_camp');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
