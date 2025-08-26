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
        Schema::create('whatsapp_user_behaviors', function (Blueprint $table) {
            
            $table->id();
            $table->string("phone");
            $table->string("behavior_type");
            $table->json("behavior_data");
            $table->timestamp("occurred_at")->useCurrent();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_user_behaviors');
    }
};
