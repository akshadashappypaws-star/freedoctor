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
        Schema::table('campaign_referrals', function (Blueprint $table) {
            // Make old phone columns nullable to support new user-based system
            $table->string('referrer_phone')->nullable()->change();
            $table->string('referee_phone')->nullable()->change();
            $table->decimal('commission', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_referrals', function (Blueprint $table) {
            // Revert phone columns to required
            $table->string('referrer_phone')->nullable(false)->change();
            $table->string('referee_phone')->nullable(false)->change();
            $table->decimal('commission', 8, 2)->default(0)->change();
        });
    }
};
