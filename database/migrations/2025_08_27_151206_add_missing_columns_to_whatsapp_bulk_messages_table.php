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
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable()->after('id');
            $table->json('parameters')->nullable()->after('message_content');
            $table->json('media_attachments')->nullable()->after('parameters');
            $table->unsignedBigInteger('flow_id')->nullable()->after('media_attachments');
            $table->string('business_phone_number_id')->nullable()->after('flow_id');
            $table->json('targeting_criteria')->nullable()->after('recipients');
            $table->timestamp('sent_at')->nullable()->after('scheduled_at');
            $table->timestamp('completed_at')->nullable()->after('sent_at');
            $table->json('delivery_stats')->nullable()->after('failed_count');
            $table->text('error_log')->nullable()->after('delivery_stats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->dropColumn([
                'template_id', 'parameters', 'media_attachments', 'flow_id',
                'business_phone_number_id', 'targeting_criteria', 'sent_at',
                'completed_at', 'delivery_stats', 'error_log'
            ]);
        });
    }
};
