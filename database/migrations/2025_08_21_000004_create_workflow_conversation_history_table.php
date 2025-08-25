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
        if (!Schema::hasTable('workflow_conversation_history')) {
            Schema::create('workflow_conversation_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workflow_id')->nullable()->constrained('workflows')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('whatsapp_number');
                $table->string('whatsapp_name')->nullable();
                $table->enum('message_type', ['incoming', 'outgoing']);
                $table->longText('message_content');
                $table->string('message_id')->nullable(); // WhatsApp message ID
                $table->enum('content_type', ['text', 'image', 'document', 'audio', 'video', 'location', 'contact'])->default('text');
                $table->string('media_url')->nullable();
                $table->boolean('is_template_message')->default(false);
                $table->string('template_name')->nullable();
                $table->longText('template_parameters')->nullable();
                $table->enum('delivery_status', ['sent', 'delivered', 'read', 'failed'])->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['whatsapp_number', 'created_at']);
                $table->index(['workflow_id', 'message_type']);
                $table->index(['message_type', 'created_at']);
                $table->index(['delivery_status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_conversation_history');
    }
};
