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
        Schema::table('campaign_sponsors', function (Blueprint $table) {
            // Add amount column and copy data from sponsored_amount
            $table->decimal('amount', 10, 2)->default(0)->after('sponsor_contact');
        });
        
        // Copy data from sponsored_amount to amount
        DB::statement('UPDATE campaign_sponsors SET amount = sponsored_amount');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_sponsors', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
};
