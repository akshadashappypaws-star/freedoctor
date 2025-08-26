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
        Schema::create('whatsapp_auto_replies', function (Blueprint $table) {
            
            $table->id();
            $table->string("trigger_keyword");
            $table->text("reply_message");
            $table->string("reply_type")->default("text");
            $table->boolean("active")->default(true);
            $table->integer("priority")->default(1);
            $table->json("conditions")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_auto_replies');
    }
};
