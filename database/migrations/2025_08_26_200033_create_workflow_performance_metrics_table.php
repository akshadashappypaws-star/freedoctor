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
            $table->unsignedBigInteger("workflow_id");
            $table->date("metric_date");
            $table->integer("executions_count")->default(0);
            $table->integer("successful_executions")->default(0);
            $table->integer("failed_executions")->default(0);
            $table->decimal("average_execution_time", 8, 2)->default(0);
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
            $table->unique(["workflow_id", "metric_date"]);
        
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
