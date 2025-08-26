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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            
            $table->id();
            $table->string("message_id")->unique();
            $table->string("phone");
            $table->text("message_body");
            $table->string("message_type")->default("text");
            $table->string("direction")->default("inbound"); // inbound/outbound
            $table->string("status")->default("received");
            $table->json("metadata")->nullable();
            $table->timestamp("sent_at")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
