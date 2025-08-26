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
        Schema::create('whatsapp_chatgpt_prompts', function (Blueprint $table) {
            
            $table->id();
            $table->string("name");
            $table->text("prompt");
            $table->string("category")->default("general");
            $table->boolean("active")->default(true);
            $table->json("parameters")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chatgpt_prompts');
    }
};
