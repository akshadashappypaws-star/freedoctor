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
        Schema::create('whatsapp_lead_scores', function (Blueprint $table) {
            
            $table->id();
            $table->string("phone")->unique();
            $table->integer("engagement_score")->default(0);
            $table->integer("response_rate")->default(0);
            $table->integer("conversion_probability")->default(0);
            $table->json("interaction_history")->nullable();
            $table->timestamp("last_calculated");
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_lead_scores');
    }
};
