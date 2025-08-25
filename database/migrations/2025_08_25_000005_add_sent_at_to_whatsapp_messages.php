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
        if (!Schema::hasColumn('whatsapp_messages', 'sent_at')) {
            Schema::table('whatsapp_messages', function (Blueprint $table) {
                $table->timestamp('sent_at')->nullable()->after('message');
                $table->index('sent_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('whatsapp_messages', 'sent_at')) {
            Schema::table('whatsapp_messages', function (Blueprint $table) {
                $table->dropIndex(['sent_at']);
                $table->dropColumn('sent_at');
            });
        }
    }
};
