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
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->integer('step_number')->default(1);
            $table->string('machine_type'); // ai, function, datatable, template, visualization
            $table->string('machine_action')->nullable();
            $table->json('input_json')->nullable();
            $table->json('output_json')->nullable();
            $table->string('status')->default('pending'); // pending, running, completed, failed
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('execution_time_ms')->nullable();
            $table->timestamps();

            $table->index(['workflow_id', 'status']);
            $table->index(['machine_type', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_logs');
    }
};
