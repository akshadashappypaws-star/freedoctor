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
        Schema::table('whatsapp_ai_analysis', function (Blueprint $table) {
            $table->string('analysis_type')->nullable()->after('ai_response');
            $table->string('analysis_result')->nullable()->after('analysis_type');
            $table->decimal('confidence_score', 5, 2)->nullable()->after('analysis_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_ai_analysis', function (Blueprint $table) {
            $table->dropColumn(['analysis_type', 'analysis_result', 'confidence_score']);
        });
    }
};
