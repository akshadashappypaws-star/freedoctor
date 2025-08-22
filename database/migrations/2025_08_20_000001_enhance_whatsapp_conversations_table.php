<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceWhatsappConversationsTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->string('sentiment')->nullable();
            $table->string('lead_status')->default('new');
            $table->string('response_type')->nullable();
            $table->integer('positive_replies_count')->default(0);
            $table->timestamp('last_interaction')->nullable();
            $table->timestamp('next_follow_up')->nullable();
            $table->json('conversation_history')->nullable();
        });
    }

    public function down()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropColumn([
                'sentiment',
                'lead_status',
                'response_type',
                'positive_replies_count',
                'last_interaction',
                'next_follow_up',
                'conversation_history'
            ]);
        });
    }
}
