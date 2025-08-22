<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappBotTables extends Migration
{
    public function up()
    {
        // Base tables
        if (!Schema::hasTable('whatsapp_templates')) {
            Schema::create('whatsapp_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('content');
                $table->json('parameters')->nullable();
                $table->string('language', 2)->default('en');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_conversations')) {
            Schema::create('whatsapp_conversations', function (Blueprint $table) {
                $table->id();
                $table->string('phone');
                $table->text('message');
                $table->string('message_id')->nullable();
                $table->string('sentiment')->nullable();
                $table->string('lead_status')->default('new');
                $table->string('response_type')->nullable();
                $table->integer('positive_replies_count')->default(0);
                $table->timestamp('last_interaction')->nullable();
                $table->timestamp('next_follow_up')->nullable();
                $table->json('conversation_history')->nullable();
                $table->boolean('is_responded')->default(false);
                $table->integer('response_time')->nullable();
                $table->json('response_schedule')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_auto_replies')) {
            Schema::create('whatsapp_auto_replies', function (Blueprint $table) {
                $table->id();
                $table->string('keyword');
                $table->enum('reply_type', ['text', 'template', 'gpt']);
                $table->text('reply_content')->nullable();
                $table->unsignedBigInteger('template_id')->nullable();
                $table->string('gpt_prompt')->nullable();
                $table->string('sentiment_type')->nullable();
                $table->integer('priority')->default(1);
                $table->unsignedBigInteger('follow_up_template_id')->nullable();
                $table->integer('follow_up_delay')->nullable();
                $table->boolean('smart_selection')->default(true);
                $table->integer('success_count')->default(0);
                $table->integer('fail_count')->default(0);
                $table->json('delay_rules')->nullable();
                $table->boolean('use_smart_delay')->default(false);
                $table->integer('min_delay_seconds')->default(30);
                $table->integer('max_delay_seconds')->default(300);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Add foreign keys if they don't exist
        if (Schema::hasTable('whatsapp_auto_replies') && Schema::hasTable('whatsapp_templates')) {
            try {
                Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
                    $table->foreign('template_id')
                          ->references('id')
                          ->on('whatsapp_templates')
                          ->onDelete('set null');
                    
                    $table->foreign('follow_up_template_id')
                          ->references('id')
                          ->on('whatsapp_templates')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign keys may already exist
            }
        }

        // Lead management tables
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

        // Message flow tables
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

        // Default responses and AI context tables
        if (!Schema::hasTable('whatsapp_default_responses')) {
            Schema::create('whatsapp_default_responses', function (Blueprint $table) {
                $table->id();
                $table->string('topic');
                $table->text('question_pattern');
                $table->text('answer');
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

        // Bulk messaging table
        if (!Schema::hasTable('whatsapp_bulk_messages_new')) {  // Using different name to avoid conflict
            Schema::create('whatsapp_bulk_messages_new', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('template_id');
                $table->json('recipients');
                $table->json('parameters')->nullable();
                $table->boolean('is_scheduled')->default(false);
                $table->timestamp('scheduled_at')->nullable();
                $table->integer('total_recipients');
                $table->string('status')->default('pending');
                $table->enum('target_category', ['valuable', 'average', 'not_interested'])->nullable();
                $table->unsignedBigInteger('flow_id')->nullable();
                $table->json('response_stats')->nullable();
                $table->timestamps();
            });

            // Add foreign keys
            try {
                Schema::table('whatsapp_bulk_messages_new', function (Blueprint $table) {
                    $table->foreign('template_id')
                          ->references('id')
                          ->on('whatsapp_templates')
                          ->onDelete('cascade');
                    
                    $table->foreign('flow_id')
                          ->references('id')
                          ->on('whatsapp_message_flows')
                          ->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign keys may already exist
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_bulk_messages');
        Schema::dropIfExists('whatsapp_chatgpt_contexts');
        Schema::dropIfExists('whatsapp_default_responses');
        Schema::dropIfExists('whatsapp_message_flows');
        Schema::dropIfExists('whatsapp_lead_scores');
        Schema::dropIfExists('whatsapp_auto_replies');
        Schema::dropIfExists('whatsapp_conversations');
        Schema::dropIfExists('whatsapp_templates');
    }
}
