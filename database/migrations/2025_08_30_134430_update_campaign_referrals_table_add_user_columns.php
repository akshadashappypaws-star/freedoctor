<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('campaign_referrals', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('campaign_referrals', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('campaign_id');
            }
            if (!Schema::hasColumn('campaign_referrals', 'referrer_user_id')) {
                $table->unsignedBigInteger('referrer_user_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('campaign_referrals', 'per_campaign_refer_cost')) {
                $table->decimal('per_campaign_refer_cost', 10, 2)->default(0)->after('referrer_user_id');
            }
            if (!Schema::hasColumn('campaign_referrals', 'referral_code')) {
                $table->string('referral_code')->nullable()->after('per_campaign_refer_cost');
            }
            if (!Schema::hasColumn('campaign_referrals', 'registration_completed_at')) {
                $table->timestamp('registration_completed_at')->nullable()->after('referral_code');
            }
            if (!Schema::hasColumn('campaign_referrals', 'notes')) {
                $table->text('notes')->nullable()->after('registration_completed_at');
            }
        });
        
        // Add foreign key constraints in a separate schema call to avoid conflicts
        Schema::table('campaign_referrals', function (Blueprint $table) {
            // Check if foreign keys don't exist before adding them
            $foreignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'campaign_referrals' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ", [config('database.connections.mysql.database')]))->pluck('CONSTRAINT_NAME');
            
            if (!$foreignKeys->contains('campaign_referrals_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$foreignKeys->contains('campaign_referrals_referrer_user_id_foreign')) {
                $table->foreign('referrer_user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_referrals', function (Blueprint $table) {
            // Remove foreign key constraints first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['referrer_user_id']);
            
            // Drop added columns
            $table->dropColumn([
                'user_id',
                'referrer_user_id', 
                'per_campaign_refer_cost',
                'referral_code',
                'registration_completed_at',
                'notes'
            ]);
        });
    }
};
