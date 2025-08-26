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
            $table->string("name");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("phone");
            $table->string("license_number")->unique();
            $table->string("specialization");
            $table->text("qualification");
            $table->integer("experience");
            $table->decimal("consultation_fee", 8, 2);
            $table->text("bio")->nullable();
            $table->string("profile_photo")->nullable();
            $table->string("status")->default("pending");
            $table->json("documents")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->string("address")->nullable();
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
