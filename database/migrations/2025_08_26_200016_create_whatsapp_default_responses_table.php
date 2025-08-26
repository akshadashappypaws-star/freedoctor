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
        Schema::create('whatsapp_default_responses', function (Blueprint $table) {
            
            $table->id();
            $table->string("response_type");
            $table->text("message");
            $table->boolean("active")->default(true);
            $table->json("conditions")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_default_responses');
    }
};
