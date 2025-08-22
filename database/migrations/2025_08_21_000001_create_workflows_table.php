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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'paused'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // WhatsApp user
            $table->string('whatsapp_number')->nullable(); // User's WhatsApp number
            $table->string('intent')->nullable(); // AI detected intent
            $table->longText('json_plan')->nullable(); // AI-generated workflow plan
            $table->longText('context_data')->nullable(); // Additional context for workflow
            $table->integer('current_step')->default(0);
            $table->integer('total_steps')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('execution_time_ms')->nullable(); // Performance tracking
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['whatsapp_number', 'created_at']);
            $table->index(['intent', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
