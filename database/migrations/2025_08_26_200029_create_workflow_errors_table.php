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
            $table->unsignedBigInteger("workflow_log_id");
            $table->string("error_type");
            $table->text("error_message");
            $table->json("error_context")->nullable();
            $table->timestamp("occurred_at")->useCurrent();
            $table->timestamps();
            $table->foreign("workflow_log_id")->references("id")->on("workflow_logs")->onDelete("cascade");
        
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
