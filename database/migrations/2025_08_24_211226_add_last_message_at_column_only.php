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
        // Check if column doesn't exist before adding
        if (!Schema::hasColumn('whatsapp_conversations', 'last_message_at')) {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                $table->timestamp('last_message_at')->nullable()->after('updated_at');
                $table->index('last_message_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('whatsapp_conversations', 'last_message_at')) {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                $table->dropIndex(['last_message_at']);
                $table->dropColumn('last_message_at');
            });
        }
    }
};
