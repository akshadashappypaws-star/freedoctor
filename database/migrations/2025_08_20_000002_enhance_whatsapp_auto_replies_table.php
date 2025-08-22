<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceWhatsappAutoRepliesTable extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $table->string('sentiment_type')->nullable();
            $table->integer('priority')->default(1);
            $table->unsignedBigInteger('follow_up_template_id')->nullable();
            $table->integer('follow_up_delay')->nullable();
            $table->boolean('smart_selection')->default(true);
            $table->integer('success_count')->default(0);
            $table->integer('fail_count')->default(0);
            
            $table->foreign('follow_up_template_id')
                  ->references('id')
                  ->on('whatsapp_templates')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $table->dropForeign(['follow_up_template_id']);
            $table->dropColumn([
                'sentiment_type',
                'priority',
                'follow_up_template_id',
                'follow_up_delay',
                'smart_selection',
                'success_count',
                'fail_count'
            ]);
        });
    }
}
