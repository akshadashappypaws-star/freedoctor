<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('whatsapp_auto_replies', 'sentiment_type')) {
                $table->string('sentiment_type')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'priority')) {
                $table->integer('priority')->default(5);
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'follow_up_template_id')) {
                $table->unsignedBigInteger('follow_up_template_id')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'follow_up_delay')) {
                $table->integer('follow_up_delay')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'smart_selection')) {
                $table->boolean('smart_selection')->default(true);
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'use_smart_delay')) {
                $table->boolean('use_smart_delay')->default(true);
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'min_delay_seconds')) {
                $table->integer('min_delay_seconds')->default(30);
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'max_delay_seconds')) {
                $table->integer('max_delay_seconds')->default(300);
            }
        });

        // Add foreign key constraint if it doesn't exist
        if (Schema::hasColumn('whatsapp_auto_replies', 'follow_up_template_id')) {
            Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
                try {
                    $table->foreign('follow_up_template_id')
                          ->references('id')
                          ->on('whatsapp_templates')
                          ->onDelete('set null');
                } catch (\Exception $e) {
                    // Foreign key may already exist
                }
            });
        }
    }

    public function down()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            // Remove foreign key first
            $table->dropForeign(['follow_up_template_id']);

            // Remove columns
            $table->dropColumn([
                'sentiment_type',
                'priority',
                'follow_up_template_id',
                'follow_up_delay',
                'smart_selection',
                'use_smart_delay',
                'min_delay_seconds',
                'max_delay_seconds'
            ]);
        });
    }
};
