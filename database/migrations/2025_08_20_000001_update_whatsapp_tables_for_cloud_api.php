<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWhatsappTablesForCloudApi extends Migration
{
    public function up()
    {
        // Update whatsapp_templates table
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_templates', 'status')) {
                $table->string('status')->default('APPROVED')->after('language');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'category')) {
                $table->string('category')->default('MARKETING')->after('language');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'components')) {
                $table->json('components')->nullable()->after('content');
            }
        });

        // Update whatsapp_conversations table
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_conversations', 'message_id')) {
                $table->string('message_id')->nullable()->after('message');
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'status')) {
                $table->string('status')->default('pending')->after('message_id');
            }
            if (!Schema::hasColumn('whatsapp_conversations', 'response')) {
                $table->text('response')->nullable()->after('status');
            }
        });

        // Update whatsapp_bulk_messages table
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'successful_count')) {
                $table->integer('successful_count')->default(0)->after('total_recipients');
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'failed_count')) {
                $table->integer('failed_count')->default(0)->after('successful_count');
            }
            if (!Schema::hasColumn('whatsapp_bulk_messages', 'errors')) {
                $table->json('errors')->nullable()->after('failed_count');
            }
        });
    }

    public function down()
    {
        // Remove added columns from whatsapp_templates
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->dropColumn(['status', 'category', 'components']);
        });

        // Remove added columns from whatsapp_conversations
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropColumn(['message_id', 'status', 'response']);
        });

        // Remove added columns from whatsapp_bulk_messages
        Schema::table('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->dropColumn(['successful_count', 'failed_count', 'errors']);
        });
    }
}
