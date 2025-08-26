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
        Schema::create('whatsapp_system_healths', function (Blueprint $table) {
            
            $table->id();
            $table->string("component");
            $table->string("status");
            $table->json("metrics");
            $table->text("error_message")->nullable();
            $table->timestamp("checked_at")->useCurrent();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_system_healths');
    }
};
