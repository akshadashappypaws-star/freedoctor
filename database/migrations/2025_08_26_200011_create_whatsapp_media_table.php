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
        Schema::create('whatsapp_media', function (Blueprint $table) {
            
            $table->id();
            $table->string("media_id")->unique();
            $table->string("filename");
            $table->string("mime_type");
            $table->bigInteger("file_size");
            $table->string("media_type"); // image, document, audio, video
            $table->string("url")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_media');
    }
};
