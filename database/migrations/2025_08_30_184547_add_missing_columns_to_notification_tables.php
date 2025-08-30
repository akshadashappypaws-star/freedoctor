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
        // Add missing columns to admin_messages table
        Schema::table('admin_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_messages', 'data')) {
                $table->json('data')->nullable()->after('type');
            }
        });

        // Add missing columns to doctor_messages table
        Schema::table('doctor_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('doctor_messages', 'campaign_id')) {
                $table->unsignedBigInteger('campaign_id')->nullable()->after('doctor_id');
            }
            if (!Schema::hasColumn('doctor_messages', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('campaign_id');
            }
            if (!Schema::hasColumn('doctor_messages', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('message');
            }
            if (!Schema::hasColumn('doctor_messages', 'read')) {
                $table->boolean('read')->default(false)->after('status');
            }
            if (!Schema::hasColumn('doctor_messages', 'data')) {
                $table->json('data')->nullable()->after('read');
            }
            
            // Add foreign key constraints
            if (Schema::hasColumn('doctor_messages', 'campaign_id')) {
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            }
            if (Schema::hasColumn('doctor_messages', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });

        // Add missing columns to user_messages table
        Schema::table('user_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('user_messages', 'read')) {
                $table->boolean('read')->default(false)->after('status');
            }
            if (!Schema::hasColumn('user_messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('read');
            }
            if (!Schema::hasColumn('user_messages', 'data')) {
                $table->json('data')->nullable()->after('is_read');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from admin_messages table
        Schema::table('admin_messages', function (Blueprint $table) {
            if (Schema::hasColumn('admin_messages', 'data')) {
                $table->dropColumn('data');
            }
        });

        // Remove columns from doctor_messages table
        Schema::table('doctor_messages', function (Blueprint $table) {
            if (Schema::hasColumn('doctor_messages', 'campaign_id')) {
                $table->dropForeign(['campaign_id']);
                $table->dropColumn('campaign_id');
            }
            if (Schema::hasColumn('doctor_messages', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('doctor_messages', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('doctor_messages', 'read')) {
                $table->dropColumn('read');
            }
            if (Schema::hasColumn('doctor_messages', 'data')) {
                $table->dropColumn('data');
            }
        });

        // Remove columns from user_messages table
        Schema::table('user_messages', function (Blueprint $table) {
            if (Schema::hasColumn('user_messages', 'read')) {
                $table->dropColumn('read');
            }
            if (Schema::hasColumn('user_messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('user_messages', 'data')) {
                $table->dropColumn('data');
            }
        });
    }
};
