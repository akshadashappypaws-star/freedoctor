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
            // Add location coordinates and related fields
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('location_address')->nullable()->after('longitude');
            $table->string('location_source')->nullable()->after('location_address'); // 'gps', 'manual', 'api'
            $table->timestamp('location_updated_at')->nullable()->after('location_source');
            $table->boolean('location_permission_granted')->default(false)->after('location_updated_at');
            $table->string('ip_address')->nullable()->after('location_permission_granted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'location_address',
                'location_source',
                'location_updated_at',
                'location_permission_granted',
                'ip_address'
            ]);
        });
    }
};
