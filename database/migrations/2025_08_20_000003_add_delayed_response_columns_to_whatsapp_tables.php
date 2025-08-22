<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDelayedResponseColumnsToWhatsappTables extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->boolean('is_responded')->default(false);
            $table->integer('response_time')->nullable();
            $table->string('message_id')->nullable();
            $table->json('response_schedule')->nullable();
        });

        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $table->json('delay_rules')->nullable();
            $table->boolean('use_smart_delay')->default(false);
            $table->integer('min_delay_seconds')->default(30);
            $table->integer('max_delay_seconds')->default(300);
        });
    }

    public function down()
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropColumn(['is_responded', 'response_time', 'message_id', 'response_schedule']);
        });

        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $table->dropColumn(['delay_rules', 'use_smart_delay', 'min_delay_seconds', 'max_delay_seconds']);
        });
    }
}
