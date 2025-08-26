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
        Schema::create('whatsapp_ai_analysis', function (Blueprint $table) {
            
            $table->id();
            $table->string("phone");
            $table->text("message_content");
            $table->string("sentiment")->nullable();
            $table->decimal("sentiment_score", 3, 2)->nullable();
            $table->string("intent")->nullable();
            $table->json("entities")->nullable();
            $table->json("ai_response")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_ai_analysis');
    }
};
