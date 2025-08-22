<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingWhatsappBotTables extends Migration
{
    public function up()
    {
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

        // Add new columns to existing tables
        if (Schema::hasTable('whatsapp_conversations')) {
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

        if (Schema::hasTable('whatsapp_auto_replies')) {
            Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
                if (!Schema::hasColumn('whatsapp_auto_replies', 'sentiment_type')) {
                    $table->string('sentiment_type')->nullable();
                }
                if (!Schema::hasColumn('whatsapp_auto_replies', 'priority')) {
                    $table->integer('priority')->default(1);
                }
                if (!Schema::hasColumn('whatsapp_auto_replies', 'follow_up_delay')) {
                    $table->integer('follow_up_delay')->nullable();
                }
                if (!Schema::hasColumn('whatsapp_auto_replies', 'smart_selection')) {
                    $table->boolean('smart_selection')->default(true);
                }
                if (!Schema::hasColumn('whatsapp_auto_replies', 'success_count')) {
                    $table->integer('success_count')->default(0);
                }
                if (!Schema::hasColumn('whatsapp_auto_replies', 'fail_count')) {
                    $table->integer('fail_count')->default(0);
                }
            });
        }

        if (Schema::hasTable('whatsapp_bulk_messages')) {
            Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('whatsapp_bulk_messages', 'target_category')) {
                    $table->enum('target_category', ['valuable', 'average', 'not_interested'])->nullable();
                }
                if (!Schema::hasColumn('whatsapp_bulk_messages', 'flow_id')) {
                    $table->unsignedBigInteger('flow_id')->nullable();
                    $table->foreign('flow_id')
                          ->references('id')
                          ->on('whatsapp_message_flows')
                          ->onDelete('set null');
                }
                if (!Schema::hasColumn('whatsapp_bulk_messages', 'response_stats')) {
                    $table->json('response_stats')->nullable();
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_default_responses');
        Schema::dropIfExists('whatsapp_lead_scores');
        Schema::dropIfExists('whatsapp_message_flows');
        Schema::dropIfExists('whatsapp_chatgpt_contexts');

        if (Schema::hasTable('whatsapp_conversations')) {
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

        if (Schema::hasTable('whatsapp_auto_replies')) {
            Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
                $table->dropColumn([
                    'sentiment_type',
                    'priority',
                    'follow_up_delay',
                    'smart_selection',
                    'success_count',
                    'fail_count'
                ]);
            });
        }

        if (Schema::hasTable('whatsapp_bulk_messages')) {
            Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
                $table->dropForeign(['flow_id']);
                $table->dropColumn([
                    'target_category',
                    'flow_id',
                    'response_stats'
                ]);
            });
        }
    }
}
