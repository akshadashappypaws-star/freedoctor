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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('whatsapp_number', 20)->nullable();
            $table->string('intent')->nullable();
            $table->json('json_plan')->nullable();
            $table->json('context_data')->nullable();
            $table->integer('current_step')->default(0);
            $table->integer('total_steps')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->bigInteger('execution_time_ms')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('whatsapp_number');
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
