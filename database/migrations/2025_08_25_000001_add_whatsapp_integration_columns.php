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
        // Add integration columns to whatsapp_conversations
        if (Schema::hasTable('whatsapp_conversations')) {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('whatsapp_conversations', 'ai_analysis_id')) {
                    $table->unsignedBigInteger('ai_analysis_id')->nullable()->after('response_schedule');
                    $table->foreign('ai_analysis_id')->references('id')->on('whatsapp_ai_analysis')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('whatsapp_conversations', 'automation_rule_id')) {
                    $table->unsignedBigInteger('automation_rule_id')->nullable()->after('ai_analysis_id');
                    $table->foreign('automation_rule_id')->references('id')->on('whatsapp_automation_rules')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('whatsapp_conversations', 'user_behavior_id')) {
                    $table->unsignedBigInteger('user_behavior_id')->nullable()->after('automation_rule_id');
                    $table->foreign('user_behavior_id')->references('id')->on('whatsapp_user_behavior')->onDelete('set null');
                }
            });
        }

        // Add integration columns to whatsapp_lead_scores
        if (Schema::hasTable('whatsapp_lead_scores')) {
            Schema::table('whatsapp_lead_scores', function (Blueprint $table) {
                if (!Schema::hasColumn('whatsapp_lead_scores', 'ai_analysis_id')) {
                    $table->unsignedBigInteger('ai_analysis_id')->nullable()->after('is_active');
                    $table->foreign('ai_analysis_id')->references('id')->on('whatsapp_ai_analysis')->onDelete('set null');
                }
                
                if (!Schema::hasColumn('whatsapp_lead_scores', 'automation_triggered')) {
                    $table->boolean('automation_triggered')->default(false)->after('ai_analysis_id');
                }
                
                if (!Schema::hasColumn('whatsapp_lead_scores', 'behavior_score')) {
                    $table->decimal('behavior_score', 5, 2)->nullable()->after('automation_triggered');
                }
            });
        }

        // Create whatsapp_messages table
        if (!Schema::hasTable('whatsapp_messages')) {
            Schema::create('whatsapp_messages', function (Blueprint $table) {
                $table->id();
                $table->string('message_id')->unique();
                $table->string('from_number');
                $table->string('to_number');
                $table->enum('message_type', ['text', 'image', 'audio', 'video', 'document', 'location', 'template'])->default('text');
                $table->text('message_body')->nullable();
                $table->json('webhook_data')->nullable();
                $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
                $table->enum('direction', ['inbound', 'outbound'])->default('outbound');
                $table->timestamp('message_timestamp')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['from_number', 'to_number']);
                $table->index('message_timestamp');
                $table->index('status');
            });
        }

        // Create whatsapp_automation_logs table
        if (!Schema::hasTable('whatsapp_automation_logs')) {
            Schema::create('whatsapp_automation_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('automation_rule_id');
                $table->string('phone_number');
                $table->text('trigger_message');
                $table->text('response_sent')->nullable();
                $table->enum('execution_status', ['success', 'failed', 'pending'])->default('pending');
                $table->text('error_message')->nullable();
                $table->decimal('execution_time', 8, 3)->nullable(); // in seconds
                $table->json('context_data')->nullable();
                $table->timestamp('executed_at');
                $table->timestamps();

                $table->foreign('automation_rule_id')->references('id')->on('whatsapp_automation_rules')->onDelete('cascade');
                $table->index(['phone_number', 'executed_at']);
                $table->index('execution_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys and columns from whatsapp_conversations
        if (Schema::hasTable('whatsapp_conversations')) {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                if (Schema::hasColumn('whatsapp_conversations', 'ai_analysis_id')) {
                    $table->dropForeign(['ai_analysis_id']);
                    $table->dropColumn('ai_analysis_id');
                }
                if (Schema::hasColumn('whatsapp_conversations', 'automation_rule_id')) {
                    $table->dropForeign(['automation_rule_id']);
                    $table->dropColumn('automation_rule_id');
                }
                if (Schema::hasColumn('whatsapp_conversations', 'user_behavior_id')) {
                    $table->dropForeign(['user_behavior_id']);
                    $table->dropColumn('user_behavior_id');
                }
            });
        }

        // Drop foreign keys and columns from whatsapp_lead_scores
        if (Schema::hasTable('whatsapp_lead_scores')) {
            Schema::table('whatsapp_lead_scores', function (Blueprint $table) {
                if (Schema::hasColumn('whatsapp_lead_scores', 'ai_analysis_id')) {
                    $table->dropForeign(['ai_analysis_id']);
                    $table->dropColumn('ai_analysis_id');
                }
                if (Schema::hasColumn('whatsapp_lead_scores', 'automation_triggered')) {
                    $table->dropColumn('automation_triggered');
                }
                if (Schema::hasColumn('whatsapp_lead_scores', 'behavior_score')) {
                    $table->dropColumn('behavior_score');
                }
            });
        }

        // Drop tables
        Schema::dropIfExists('whatsapp_automation_logs');
        Schema::dropIfExists('whatsapp_messages');
    }
};
