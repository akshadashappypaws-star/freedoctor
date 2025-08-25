<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappLeadManagementTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('whatsapp_lead_scores')) {
            Schema::create('whatsapp_lead_scores', function (Blueprint $table) {
                $table->id();
                $table->string('phone')->unique();
                $table->enum('category', ['valuable', 'average', 'not_interested'])->default('average');
                $table->integer('interaction_score')->default(0);
                $table->integer('response_rate')->default(0);
                $table->integer('appointment_count')->default(0);
                $table->integer('query_count')->default(0);
                $table->timestamp('last_interaction')->nullable();
                $table->json('interaction_history')->nullable();
                $table->json('interest_topics')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_message_flows')) {
            Schema::create('whatsapp_message_flows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->enum('target_category', ['valuable', 'average', 'not_interested']);
                $table->json('flow_steps');
                $table->integer('success_count')->default(0);
                $table->integer('failure_count')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_default_responses')) {
            Schema::create('whatsapp_default_responses', function (Blueprint $table) {
                $table->id();
                $table->string('topic');
                $table->text('question_pattern');
                $table->text('answer');
                $table->string('template_id')->nullable();
                $table->json('parameters')->nullable();
                $table->integer('usage_count')->default(0);
                $table->float('success_rate')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_chatgpt_contexts')) {
            Schema::create('whatsapp_chatgpt_contexts', function (Blueprint $table) {
                $table->id();
                $table->string('topic');
                $table->text('system_message');
                $table->json('context_data');
                $table->text('sample_questions')->nullable();
                $table->text('sample_responses')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_lead_scores');
        Schema::dropIfExists('whatsapp_message_flows');
        Schema::dropIfExists('whatsapp_default_responses');
        Schema::dropIfExists('whatsapp_chatgpt_contexts');
    }
}
