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
        Schema::create('whatsapp_chatgpt_contexts', function (Blueprint $table) {
            
            $table->id();
            $table->string("phone");
            $table->json("conversation_history");
            $table->string("current_topic")->nullable();
            $table->json("user_preferences")->nullable();
            $table->timestamp("last_interaction");
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chatgpt_contexts');
    }
};
