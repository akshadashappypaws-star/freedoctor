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
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            // Add missing columns for WhatsApp API integration
            $table->string('whatsapp_id')->nullable()->after('id'); // WhatsApp template ID from API
            $table->string('status')->default('PENDING')->after('name'); // APPROVED, PENDING, REJECTED
            $table->string('category')->nullable()->after('status'); // MARKETING, UTILITY, etc.
            $table->json('components')->nullable()->after('parameters'); // Template components from API
            $table->json('meta_data')->nullable()->after('components'); // Additional metadata
            
            // Add index for faster lookups
            $table->index('whatsapp_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->dropIndex(['whatsapp_id']);
            $table->dropIndex(['status']);
            $table->dropColumn(['whatsapp_id', 'status', 'category', 'components', 'meta_data']);
        });
    }
};
