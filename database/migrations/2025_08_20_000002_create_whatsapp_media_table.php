<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('whatsapp_media')) {
            Schema::create('whatsapp_media', function (Blueprint $table) {
                $table->id();
                $table->string('phone');
                $table->string('message_id');
                $table->string('media_type');
                $table->string('file_path');
                $table->json('analysis_data')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamp('analyzed_at')->nullable();
                $table->timestamps();

                $table->index(['phone', 'message_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_media');
    }
};
