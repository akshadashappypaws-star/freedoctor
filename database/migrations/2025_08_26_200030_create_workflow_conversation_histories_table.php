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
        Schema::create('workflow_conversation_histories', function (Blueprint $table) {
            
            $table->id();
            $table->string("phone");
            $table->unsignedBigInteger("workflow_id");
            $table->string("current_step");
            $table->json("conversation_data")->nullable();
            $table->json("user_responses")->nullable();
            $table->timestamp("started_at")->useCurrent();
            $table->timestamp("last_interaction")->nullable();
            $table->timestamps();
            $table->foreign("workflow_id")->references("id")->on("workflows")->onDelete("cascade");
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_conversation_histories');
    }
};
