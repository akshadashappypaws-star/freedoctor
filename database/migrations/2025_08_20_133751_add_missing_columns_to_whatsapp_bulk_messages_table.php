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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'target_category')) {
                $table->string('target_category')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'flow_id')) {
                $table->foreignId('flow_id')->nullable()->constrained('whatsapp_message_flows')->onDelete('set null');
            }
            // Add tracking columns if they don't exist
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'total_numbers')) {
                $table->integer('total_numbers')->default(0);
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->dropColumn([
                'target_category',
                'flow_id',
                'total_numbers',
                'pending_count',
                'invalid_numbers',
                'delivery_status',
                'started_at',
                'completed_at',
                'progress_percentage'
            ]);
        });
    }
};
