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
        Schema::create('user_messages', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->string("phone");
            $table->text("message");
            $table->string("type")->default("text");
            $table->string("status")->default("sent");
            $table->json("metadata")->nullable();
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("set null");
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
