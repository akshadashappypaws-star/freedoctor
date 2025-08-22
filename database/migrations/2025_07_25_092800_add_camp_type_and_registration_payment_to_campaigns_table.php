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
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'camp_type')) {
                $table->enum('camp_type', ['medical', 'surgical'])->default('medical')->after('timings');
            }
            if (!Schema::hasColumn('campaigns', 'registration_payment')) {
                $table->decimal('registration_payment', 10, 2)->default(0.00)->after('camp_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['camp_type', 'registration_payment']);
        });
    }
};
