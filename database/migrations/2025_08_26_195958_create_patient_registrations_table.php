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
        Schema::create('patient_registrations', function (Blueprint $table) {
            
            $table->id();
            $table->string("name");
            $table->string("phone")->unique();
            $table->string("email")->nullable();
            $table->integer("age");
            $table->string("gender");
            $table->text("medical_history")->nullable();
            $table->string("emergency_contact")->nullable();
            $table->string("address")->nullable();
            $table->string("status")->default("active");
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_registrations');
    }
};
