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
        // Create webhook_logs table if it doesn't exist
        if (!Schema::hasTable('webhook_logs')) {
            Schema::create('webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('general'); // whatsapp, payment, razorpay, general
                $table->string('event')->nullable();
                $table->json('payload');
                $table->json('headers')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('signature')->nullable();
                $table->boolean('verified')->default(false);
                $table->boolean('processed')->default(false);
                $table->text('error_message')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                
                $table->index(['type', 'event']);
                $table->index('created_at');
                $table->index('processed');
            });
        }
        
        // Enhance whatsapp_messages table
        if (!Schema::hasTable('whatsapp_messages')) {
            Schema::create('whatsapp_messages', function (Blueprint $table) {
                $table->id();
                $table->string('message_id')->unique();
                $table->string('from_number');
                $table->string('to_number')->nullable();
                $table->enum('message_type', ['text', 'image', 'document', 'audio', 'video', 'location', 'contacts', 'button', 'interactive', 'template']);
                $table->text('message_body')->nullable();
                $table->json('webhook_data')->nullable();
                $table->enum('status', ['received', 'sent', 'delivered', 'read', 'failed'])->default('received');
                $table->enum('direction', ['incoming', 'outgoing'])->default('incoming');
                $table->timestamp('message_timestamp');
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                $table->index(['from_number', 'created_at']);
                $table->index(['message_id', 'status']);
                $table->index('message_timestamp');
            });
        }
        
        // Create webhook_events table for detailed event tracking
        if (!Schema::hasTable('webhook_events')) {
            Schema::create('webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('webhook_type'); // whatsapp, razorpay, payment
                $table->string('event_type'); // message_received, payment_captured, etc.
                $table->string('source_id')->nullable(); // message_id, payment_id, etc.
                $table->json('event_data');
                $table->string('status')->default('pending'); // pending, processed, failed
                $table->text('processing_result')->nullable();
                $table->integer('retry_count')->default(0);
                $table->timestamp('last_retry_at')->nullable();
                $table->timestamps();
                
                $table->index(['webhook_type', 'event_type']);
                $table->index(['status', 'created_at']);
                $table->index('source_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('whatsapp_messages');
    }
};
