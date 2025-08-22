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
        Schema::create('workflow_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->foreignId('workflow_log_id')->nullable()->constrained('workflow_logs')->onDelete('cascade');
            $table->integer('step_number');
            $table->string('machine_type');
            $table->string('error_type'); // validation, api, database, timeout, etc.
            $table->text('error_message');
            $table->longText('stack_trace')->nullable();
            $table->longText('context_data')->nullable(); // Additional context when error occurred
            $table->boolean('is_recoverable')->default(false);
            $table->text('recovery_suggestion')->nullable();
            $table->timestamps();

            $table->index(['workflow_id', 'step_number']);
            $table->index(['error_type', 'created_at']);
            $table->index(['is_recoverable']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_errors');
    }
};
