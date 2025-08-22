<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_auto_replies', function (Blueprint $table) {
            $table->id();
            $table->string('keyword');
            $table->enum('reply_type', ['text', 'template', 'gpt'])->default('text');
            $table->text('reply_content')->nullable();
            $table->text('gpt_prompt')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->text('message');
            $table->text('reply')->nullable();
            $table->enum('reply_type', ['text', 'template', 'gpt'])->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_conversations');
        Schema::dropIfExists('whatsapp_auto_replies');
    }
};
