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
            $table->unsignedBigInteger('workflow_id');
            $table->string('metric_name');
            $table->decimal('metric_value', 10, 4);
            $table->string('metric_unit')->nullable();
            $table->json('additional_data')->nullable();
            $table->date('metric_date');
            $table->timestamps();
            
            $table->index(['workflow_id', 'metric_name']);
            $table->index('metric_date');
            $table->foreign('workflow_id')->references('id')->on('workflows')->onDelete('cascade');
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
