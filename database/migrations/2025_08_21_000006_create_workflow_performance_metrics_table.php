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
        Schema::create('workflow_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->onDelete('cascade');
            $table->string('metric_name'); // response_time, success_rate, user_satisfaction
            $table->decimal('metric_value', 10, 4);
            $table->string('metric_unit')->nullable(); // ms, percentage, score
            $table->longText('additional_data')->nullable(); // JSON for extra metric details
            $table->date('metric_date');
            $table->timestamps();

            $table->index(['workflow_id', 'metric_name']);
            $table->index(['metric_name', 'metric_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_performance_metrics');
    }
};
