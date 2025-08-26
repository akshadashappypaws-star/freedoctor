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
        Schema::create('whatsapp_weekly_reports', function (Blueprint $table) {
            
            $table->id();
            $table->date("week_start");
            $table->date("week_end");
            $table->json("metrics");
            $table->json("top_conversations")->nullable();
            $table->json("performance_summary");
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_weekly_reports');
    }
};
