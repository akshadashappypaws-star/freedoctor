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
            $table->string("patient_phone");
            $table->decimal("amount", 10, 2);
            $table->string("transaction_id")->unique();
            $table->string("payment_method");
            $table->string("status")->default("pending");
            $table->string("type")->default("consultation");
            $table->text("description")->nullable();
            $table->json("payment_details")->nullable();
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
