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
        Schema::table('whatsapp_user_behavior', function (Blueprint $table) {
            // Add conversation_id column with foreign key constraint
            $table->unsignedBigInteger('conversation_id')->nullable()->after('id');
            $table->index('conversation_id');
            
            // Add foreign key constraint if whatsapp_conversations table exists
            if (Schema::hasTable('whatsapp_conversations')) {
                $table->foreign('conversation_id')->references('id')->on('whatsapp_conversations')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_user_behavior', function (Blueprint $table) {
            // Drop foreign key constraint first if it exists
            if (Schema::hasTable('whatsapp_conversations')) {
                $table->dropForeign(['conversation_id']);
            }
            $table->dropIndex(['conversation_id']);
            $table->dropColumn('conversation_id');
        });
    }
};
