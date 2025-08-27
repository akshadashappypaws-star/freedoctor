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
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->string('message_id')->nullable()->after('message');
            $table->string('reply_type')->nullable()->after('reply');
            $table->timestamp('sent_at')->nullable()->after('reply_type');
            $table->boolean('is_responded')->default(false)->after('sent_at');
            $table->string('sentiment')->nullable()->after('is_responded');
            $table->string('lead_status')->nullable()->after('sentiment');
            $table->timestamp('last_interaction')->nullable()->after('lead_status');
            $table->unsignedBigInteger('user_behavior_id')->nullable()->after('last_interaction');
            $table->unsignedBigInteger('template_id')->nullable()->after('user_behavior_id');
            $table->unsignedBigInteger('auto_reply_id')->nullable()->after('template_id');
            $table->unsignedBigInteger('chatgpt_prompt_id')->nullable()->after('auto_reply_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropColumn([
                'message_id', 'reply_type', 'sent_at', 'is_responded', 
                'sentiment', 'lead_status', 'last_interaction', 
                'user_behavior_id', 'template_id', 'auto_reply_id', 
                'chatgpt_prompt_id'
            ]);
        });
    }
};
