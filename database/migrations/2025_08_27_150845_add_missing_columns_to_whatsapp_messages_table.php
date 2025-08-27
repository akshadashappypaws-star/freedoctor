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
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('conversation_id')->nullable()->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->after('conversation_id');
            $table->text('message')->nullable()->after('user_id');
            $table->string('type')->default('text')->after('message');
            $table->string('media_url')->nullable()->after('metadata');
            $table->string('media_type')->nullable()->after('media_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropColumn([
                'conversation_id', 'user_id', 'message', 'type', 
                'media_url', 'media_type'
            ]);
        });
    }
};
