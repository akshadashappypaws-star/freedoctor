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
        Schema::table('business_organization_requests', function (Blueprint $table) {
            // Update the enum to include all form values
            $table->enum('camp_request_type', [
                'medical', 
                'surgical', 
                'preventive', 
                'specialized'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_organization_requests', function (Blueprint $table) {
            // Revert to original enum
            $table->enum('camp_request_type', ['medical', 'surgical'])->change();
        });
    }
};
