<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_chatgpt_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('prompt_template');
            $table->text('system_message')->nullable();
            $table->json('allowed_parameters')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chatgpt_prompts');
    }
};
