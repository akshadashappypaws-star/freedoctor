<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingToWhatsappBulkMessages extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'total_numbers')) {
                $table->integer('total_numbers')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'sent_count')) {
                $table->integer('sent_count')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'failed_count')) {
                $table->integer('failed_count')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'pending_count')) {
                $table->integer('pending_count')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'invalid_numbers')) {
                $table->json('invalid_numbers')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'delivery_status')) {
                $table->json('delivery_status')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'started_at')) {
                $table->timestamp('started_at')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'progress_percentage')) {
                $table->float('progress_percentage')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->dropColumn([
                'total_numbers',
                'sent_count',
                'failed_count',
                'pending_count',
                'invalid_numbers',
                'delivery_status',
                'started_at',
                'completed_at',
                'progress_percentage'
            ]);
        });
    }
}
