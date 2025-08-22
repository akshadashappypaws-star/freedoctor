<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingToWhatsappBulkMessages extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->integer('total_numbers')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->integer('pending_count')->default(0);
            $table->json('invalid_numbers')->nullable();
            $table->json('delivery_status')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->float('progress_percentage')->default(0);
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
