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
            $table->unsignedBigInteger('whatsapp_template_id');
            $table->unsignedBigInteger('campaign_id');
            $table->string('trigger_event')->default('registration'); // registration, reminder, follow_up, etc.
            $table->json('dynamic_params'); // Maps template parameters to campaign data fields
            $table->boolean('is_active')->default(true);
            $table->integer('delay_minutes')->default(0); // Delay after trigger event
            $table->json('conditions')->nullable(); // Additional conditions for sending
            $table->timestamps();

            $table->foreign('whatsapp_template_id')->references('id')->on('whatsapp_templates')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->unique(['whatsapp_template_id', 'campaign_id', 'trigger_event'], 'template_campaign_trigger_unique');
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
