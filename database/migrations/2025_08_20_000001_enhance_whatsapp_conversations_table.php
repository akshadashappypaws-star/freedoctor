<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceWhatsappConversationsTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_conversations', 'sentiment')) {
                $table->string('sentiment')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'lead_status')) {
                $table->string('lead_status')->default('new');
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'response_type')) {
                $table->string('response_type')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'positive_replies_count')) {
                $table->integer('positive_replies_count')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'last_interaction')) {
                $table->timestamp('last_interaction')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'next_follow_up')) {
                $table->timestamp('next_follow_up')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'conversation_history')) {
                $table->json('conversation_history')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $columnsToCheck = [
                'sentiment',
                'lead_status',
                'response_type',
                'positive_replies_count',
                'last_interaction',
                'next_follow_up',
                'conversation_history'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('whatsapp_conversations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
