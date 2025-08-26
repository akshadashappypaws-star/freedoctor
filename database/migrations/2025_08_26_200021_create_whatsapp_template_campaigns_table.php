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
        Schema::create('whatsapp_template_campaigns', function (Blueprint $table) {
            
            $table->id();
            $table->string("campaign_name");
            $table->unsignedBigInteger("template_id");
            $table->json("recipients");
            $table->json("template_parameters")->nullable();
            $table->string("status")->default("pending");
            $table->timestamp("scheduled_at")->nullable();
            $table->timestamps();
            $table->foreign("template_id")->references("id")->on("whatsapp_templates")->onDelete("cascade");
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_template_campaigns');
    }
};
