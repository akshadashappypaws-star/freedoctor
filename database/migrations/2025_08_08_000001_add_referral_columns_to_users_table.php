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
        Schema::table('users', function (Blueprint $table) {
            $table->string('your_referral_id')->unique()->nullable()->after('status');
            $table->string('referred_by')->nullable()->after('your_referral_id');
            $table->timestamp('referral_completed_at')->nullable()->after('referred_by');
            
            // Add index for better performance on referral lookups
            $table->index('your_referral_id');
            $table->index('referred_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['your_referral_id']);
            $table->dropIndex(['referred_by']);
            $table->dropColumn(['your_referral_id', 'referred_by', 'referral_completed_at']);
        });
    }
};
