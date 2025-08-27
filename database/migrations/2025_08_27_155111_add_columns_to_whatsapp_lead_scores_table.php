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
        Schema::table('whatsapp_lead_scores', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('whatsapp_lead_scores', 'category')) {
                $table->enum('category', ['interested', 'average', 'not_interested'])->default('average');
            }
            if (!Schema::hasColumn('whatsapp_lead_scores', 'interaction_score')) {
                $table->decimal('interaction_score', 8, 2)->default(0);
            }
            if (!Schema::hasColumn('whatsapp_lead_scores', 'response_rate')) {
                $table->decimal('response_rate', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('whatsapp_lead_scores', 'total_messages')) {
                $table->integer('total_messages')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_lead_scores', 'responses_received')) {
                $table->integer('responses_received')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_lead_scores', 'engagement_data')) {
                $table->json('engagement_data')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_lead_scores', function (Blueprint $table) {
            $table->dropColumn(['category', 'interaction_score', 'response_rate', 'total_messages', 'responses_received', 'engagement_data']);
        });
    }
};
