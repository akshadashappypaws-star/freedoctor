<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->constrained('whatsapp_templates')->onDelete('set null');
            $table->foreignId('auto_reply_id')->nullable()->constrained('whatsapp_auto_replies')->onDelete('set null');
            $table->foreignId('chatgpt_prompt_id')->nullable()->constrained('whatsapp_chatgpt_prompts')->onDelete('set null');
            $table->json('parameters')->nullable(); // Store parameters used in template or ChatGPT prompt
            $table->boolean('is_processed')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropForeign(['auto_reply_id']);
            $table->dropForeign(['chatgpt_prompt_id']);
            $table->dropColumn(['template_id', 'auto_reply_id', 'chatgpt_prompt_id', 'parameters', 'is_processed']);
        });
    }
};
