<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappLeadScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('whatsapp_lead_scores')) {
            Schema::create('whatsapp_lead_scores', function (Blueprint $table) {
                $table->id();
                $table->string('phone')->unique();
                $table->string('category')->default('average'); // valuable, average, not_interested
                $table->integer('interaction_score')->default(0);
                $table->decimal('response_rate', 5, 2)->default(0);
                $table->json('interaction_history')->nullable();
                $table->string('customer_name')->nullable();
                $table->timestamp('last_interaction')->nullable();
                $table->timestamps();

                $table->index(['category', 'interaction_score']);
                $table->index('phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_lead_scores');
    }
}
