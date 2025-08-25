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
                $table->unsignedBigInteger('workflow_id');
                $table->string('message_id')->nullable();
                $table->enum('message_type', ['incoming', 'outgoing', 'system']);
                $table->text('message_content');
                $table->enum('message_format', ['text', 'image', 'document', 'audio', 'video', 'interactive'])->default('text');
                $table->string('sender')->nullable();
                $table->string('recipient')->nullable();
                $table->string('whatsapp_number')->nullable();
                $table->string('template_name')->nullable();
                $table->json('template_parameters')->nullable();
                $table->enum('delivery_status', ['pending', 'sent', 'delivered', 'read', 'failed'])->default('pending');
                $table->timestamp('message_timestamp')->useCurrent();
                $table->json('metadata')->nullable();
                $table->timestamps();
                
                $table->index(['workflow_id', 'message_timestamp'], 'wch_workflow_timestamp_idx');
                $table->index('whatsapp_number', 'wch_whatsapp_number_idx');
                $table->index('delivery_status', 'wch_delivery_status_idx');
                $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
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
