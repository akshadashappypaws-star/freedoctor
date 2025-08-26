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
        Schema::create('whatsapp_bulk_messages', function (Blueprint $table) {
            
            $table->id();
            $table->string("campaign_name");
            $table->text("message_content");
            $table->json("recipients");
            $table->string("status")->default("pending");
            $table->integer("total_count")->default(0);
            $table->integer("sent_count")->default(0);
            $table->integer("failed_count")->default(0);
            $table->timestamp("scheduled_at")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_bulk_messages');
    }
};
