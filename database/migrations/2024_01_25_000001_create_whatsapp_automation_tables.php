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
        // WhatsApp Automation Conversations
        if (!Schema::hasTable('whatsapp_conversations_automation')) {
            Schema::create('whatsapp_conversations_automation', function (Blueprint $table) {
                $table->id();
                $table->string('phone_number');
                $table->string('contact_name')->nullable();
                $table->enum('user_type', ['patient', 'doctor', 'business', 'unknown'])->default('unknown');
                $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
                $table->json('user_profile')->nullable(); // Store user preferences, history
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
                
                $table->index(['phone_number', 'status']);
                $table->index('last_message_at');
            });
        }

        // Individual Messages
        if (!Schema::hasTable('whatsapp_messages_automation')) {
            Schema::create('whatsapp_messages_automation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('whatsapp_conversations_automation')->onDelete('cascade');
                $table->enum('direction', ['incoming', 'outgoing']);
                $table->text('message_text');
                $table->string('message_type')->default('text'); // text, image, document, etc.
                $table->json('message_data')->nullable(); // media URLs, document info, etc.
                $table->boolean('is_automated')->default(false);
                $table->string('template_used')->nullable();
                $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
                $table->timestamp('sent_at');
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                $table->index(['conversation_id', 'sent_at']);
                $table->index(['is_automated', 'template_used']);
            });
        }

        // AI Analysis and Notes
        if (!Schema::hasTable('whatsapp_ai_analysis')) {
            Schema::create('whatsapp_ai_analysis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->nullable()->constrained('whatsapp_conversations_automation')->onDelete('cascade');
                $table->foreignId('message_id')->nullable()->constrained('whatsapp_messages_automation')->onDelete('cascade');
                $table->enum('analysis_type', ['intent', 'sentiment', 'category', 'urgency', 'summary', 'rule_creation', 'rule_update', 'rule_deletion', 'rule_toggle', 'rule_test', 'fallback']);
                $table->text('analysis_result');
                $table->decimal('confidence_score', 5, 2)->nullable(); // 0.00 to 100.00
                $table->json('analysis_data')->nullable(); // Additional AI analysis details
                $table->text('ai_notes')->nullable(); // Auto-generated notes
                $table->string('ai_model_used')->default('gpt-4');
                $table->timestamps();
                
                $table->index(['conversation_id', 'analysis_type']);
                $table->index('confidence_score');
            });
        }

        // User Categorization and Behavior
        if (!Schema::hasTable('whatsapp_user_behavior')) {
            Schema::create('whatsapp_user_behavior', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('whatsapp_conversations_automation')->onDelete('cascade');
                $table->enum('interest_level', ['high', 'medium', 'low', 'not_interested'])->default('medium');
                $table->enum('response_pattern', ['quick', 'delayed', 'irregular', 'no_response'])->default('irregular');
                $table->enum('engagement_type', ['interested', 'average', 'not_interested'])->default('average');
                $table->integer('total_messages')->default(0);
                $table->integer('questions_asked')->default(0);
                $table->integer('appointments_requested')->default(0);
                $table->decimal('avg_response_time', 8, 2)->nullable(); // in minutes
                $table->json('interaction_history')->nullable(); // Track interaction patterns
                $table->timestamp('last_analyzed_at')->nullable();
                $table->timestamps();
                
                $table->index(['interest_level', 'engagement_type']);
                $table->index('last_analyzed_at');
            });
        }

        // Automation Rules
        if (!Schema::hasTable('whatsapp_automation_rules')) {
            Schema::create('whatsapp_automation_rules', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->json('trigger_conditions'); // Keywords, patterns, time-based
                $table->json('actions'); // Send template, mark user, create note, etc.
                $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
                $table->boolean('is_active')->default(true);
                $table->integer('execution_count')->default(0);
                $table->integer('success_count')->default(0);
                $table->timestamp('last_executed_at')->nullable();
                $table->timestamps();
                
                $table->index(['is_active', 'priority']);
            });
        }

        // Workflow Templates
        if (!Schema::hasTable('whatsapp_workflows')) {
            Schema::create('whatsapp_workflows', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->json('workflow_steps'); // Step-by-step automation flow
                $table->enum('trigger_type', ['keyword', 'time', 'behavior', 'manual']);
                $table->json('trigger_config');
                $table->enum('status', ['active', 'paused', 'draft'])->default('draft');
                $table->integer('executions')->default(0);
                $table->decimal('success_rate', 5, 2)->default(0.00);
                $table->timestamps();
                
                $table->index(['status', 'trigger_type']);
            });
        }

        // Machine/Component Health Monitoring
        if (!Schema::hasTable('whatsapp_system_health')) {
            Schema::create('whatsapp_system_health', function (Blueprint $table) {
                $table->id();
                $table->string('component_name'); // ai, template, database, function, visualization
                $table->enum('status', ['online', 'maintenance', 'offline'])->default('online');
                $table->decimal('health_percentage', 5, 2)->default(100.00);
                $table->integer('requests_today')->default(0);
                $table->decimal('avg_response_time', 8, 3)->default(0.000); // in seconds
                $table->decimal('success_rate', 5, 2)->default(100.00);
                $table->json('performance_metrics')->nullable();
                $table->text('last_error')->nullable();
                $table->timestamp('last_check_at');
                $table->timestamps();
                
                $table->index(['component_name', 'status']);
                $table->index('last_check_at');
            });
        }

        // Weekly Reports
        if (!Schema::hasTable('whatsapp_weekly_reports')) {
            Schema::create('whatsapp_weekly_reports', function (Blueprint $table) {
                $table->id();
                $table->date('week_start');
                $table->date('week_end');
                $table->integer('total_conversations');
                $table->integer('new_conversations');
                $table->integer('total_messages');
                $table->integer('automated_messages');
                $table->json('user_categorization'); // interested, average, not_interested counts
                $table->json('popular_keywords');
                $table->json('ai_insights');
                $table->decimal('automation_efficiency', 5, 2)->default(0.00);
                $table->json('recommendations')->nullable();
                $table->timestamps();
                
                $table->index(['week_start', 'week_end']);
            });
        }

        // Template Usage Statistics
        if (!Schema::hasTable('whatsapp_template_stats')) {
            Schema::create('whatsapp_template_stats', function (Blueprint $table) {
                $table->id();
                $table->string('template_name');
                $table->integer('usage_count')->default(0);
                $table->integer('success_count')->default(0);
                $table->decimal('success_rate', 5, 2)->default(0.00);
                $table->decimal('avg_response_time', 8, 2)->default(0.00);
                $table->json('performance_data')->nullable();
                $table->date('date');
                $table->timestamps();
                
                $table->index(['template_name', 'date']);
                $table->index('success_rate');
            });
        }

        // Keyword Tracking
        if (!Schema::hasTable('whatsapp_keyword_tracking')) {
            Schema::create('whatsapp_keyword_tracking', function (Blueprint $table) {
                $table->id();
                $table->string('keyword');
                $table->integer('occurrence_count')->default(1);
                $table->json('context_examples')->nullable(); // Store example contexts
                $table->enum('sentiment', ['positive', 'neutral', 'negative'])->default('neutral');
                $table->date('tracked_date');
                $table->timestamps();
                
                $table->index(['keyword', 'tracked_date']);
                $table->index('occurrence_count');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_keyword_tracking');
        Schema::dropIfExists('whatsapp_template_stats');
        Schema::dropIfExists('whatsapp_weekly_reports');
        Schema::dropIfExists('whatsapp_system_health');
        Schema::dropIfExists('whatsapp_workflows');
        Schema::dropIfExists('whatsapp_automation_rules');
        Schema::dropIfExists('whatsapp_user_behavior');
        Schema::dropIfExists('whatsapp_ai_analysis');
        Schema::dropIfExists('whatsapp_messages_automation');
        Schema::dropIfExists('whatsapp_conversations_automation');
    }
};
