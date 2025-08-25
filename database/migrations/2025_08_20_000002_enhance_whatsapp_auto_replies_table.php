<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceWhatsappAutoRepliesTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_auto_replies', 'sentiment_type')) {
                $table->string('sentiment_type')->nullable();
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'priority')) {
                $table->integer('priority')->default(1);
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
            if (!Schema::hasColumn('whatsapp_auto_replies', 'success_count')) {
                $table->integer('success_count')->default(0);
            }
            if (!Schema::hasColumn('whatsapp_auto_replies', 'fail_count')) {
                $table->integer('fail_count')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $columnsToCheck = [
                'sentiment_type',
                'priority',
                'follow_up_template_id',
                'follow_up_delay',
                'smart_selection',
                'success_count',
                'fail_count'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('whatsapp_auto_replies', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
