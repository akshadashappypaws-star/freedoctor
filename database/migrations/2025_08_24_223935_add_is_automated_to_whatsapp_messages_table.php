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
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_messages', 'is_automated')) {
                $table->boolean('is_automated')->default(false)->after('message_data');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'template_used')) {
                $table->string('template_used')->nullable()->after('is_automated');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('template_used');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_messages', 'is_automated')) {
                $table->dropColumn('is_automated');
            }
            if (Schema::hasColumn('whatsapp_messages', 'template_used')) {
                $table->dropColumn('template_used');
            }
            if (Schema::hasColumn('whatsapp_messages', 'sent_at')) {
                $table->dropColumn('sent_at');
            }
        });
    }
};
