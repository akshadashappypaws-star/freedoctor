<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

            $table->foreign('message_id')
                  ->references('message_id')
                  ->on('whatsapp_conversations')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_media');
    }
};
