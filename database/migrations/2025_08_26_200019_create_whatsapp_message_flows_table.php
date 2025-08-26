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
        Schema::create('whatsapp_message_flows', function (Blueprint $table) {
            
            $table->id();
            $table->string("flow_name");
            $table->json("flow_steps");
            $table->string("trigger_condition");
            $table->boolean("active")->default(true);
            $table->text("description")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_flows');
    }
};
