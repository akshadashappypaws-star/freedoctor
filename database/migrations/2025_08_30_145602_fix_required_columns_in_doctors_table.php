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
        Schema::table('doctors', function (Blueprint $table) {
            // Make these columns nullable to avoid "doesn't have a default value" errors
            $table->string('license_number')->nullable()->change();
            $table->string('specialization')->nullable()->change();
            $table->text('qualification')->nullable()->change();
            $table->decimal('consultation_fee', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // Revert back to NOT NULL (but this might fail if there are NULL values)
            $table->string('license_number')->nullable(false)->change();
            $table->string('specialization')->nullable(false)->change();
            $table->text('qualification')->nullable(false)->change();
            $table->decimal('consultation_fee', 8, 2)->nullable(false)->change();
        });
    }
};
