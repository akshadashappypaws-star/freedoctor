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
        // Use raw SQL to drop the unique constraint
        DB::statement('ALTER TABLE campaign_referrals DROP INDEX campaign_referrals_user_id_campaign_id_unique');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the unique constraint if needed to rollback
        DB::statement('ALTER TABLE campaign_referrals ADD UNIQUE KEY campaign_referrals_user_id_campaign_id_unique (user_id, campaign_id)');
    }
};
