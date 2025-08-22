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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('campaigns', 'registration_payment')) {
                $table->decimal('registration_payment', 10, 2)->nullable()->after('timings');
            }
            if (!Schema::hasColumn('campaigns', 'camp_type')) {
                $table->enum('camp_type', ['medical', 'surgical'])->default('medical')->after('timings');
            }
            if (!Schema::hasColumn('campaigns', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('doctor_id');
            }
            if (!Schema::hasColumn('campaigns', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('images');
            }
            if (!Schema::hasColumn('campaigns', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('location');
            }
            if (!Schema::hasColumn('campaigns', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'registration_payment',
                'camp_type',
                'category_id',
                'thumbnail',
                'latitude',
                'longitude'
            ]);
        });
    }
};
