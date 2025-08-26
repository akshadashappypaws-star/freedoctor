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
            $table->unsignedBigInteger("workflow_id");
            $table->string("execution_id")->unique();
            $table->string("status");
            $table->json("execution_data")->nullable();
            $table->timestamp("started_at")->useCurrent();
            $table->timestamp("completed_at")->nullable();
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
        
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
