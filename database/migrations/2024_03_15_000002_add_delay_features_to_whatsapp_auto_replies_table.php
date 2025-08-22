<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
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
    }

    public function down()
    {
        Schema::table('whatsapp_auto_replies', function (Blueprint $table) {
            $table->dropColumn([
                'use_smart_delay',
                'min_delay_seconds',
                'max_delay_seconds'
            ]);
        });
    }
};
