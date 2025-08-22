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
        Schema::table('doctor_proposals', function (Blueprint $table) {
            $table->foreignId('business_organization_request_id')->nullable()
                  ->after('doctor_id')
                  ->constrained('business_organization_requests')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_proposals', function (Blueprint $table) {
            $table->dropForeign(['business_organization_request_id']);
            $table->dropColumn('business_organization_request_id');
        });
    }
};
