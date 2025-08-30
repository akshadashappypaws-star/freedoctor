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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('campaigns', 'start_time')) {
                $table->time('start_time')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'end_time')) {
                $table->time('end_time')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'camp_type')) {
                $table->enum('camp_type', ['medical', 'surgical'])->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'registration_payment')) {
                $table->decimal('registration_payment', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'per_refer_cost')) {
                $table->decimal('per_refer_cost', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'specializations')) {
                $table->json('specializations')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'contact_number')) {
                $table->string('contact_number')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'expected_patients')) {
                $table->integer('expected_patients')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'images')) {
                $table->json('images')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'video')) {
                $table->string('video')->nullable();
            }
            if (!Schema::hasColumn('campaigns', 'service_in_camp')) {
                $table->text('service_in_camp')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $columnsToDropIfExist = [
                'start_time', 'end_time', 'camp_type', 'registration_payment', 
                'per_refer_cost', 'amount', 'specializations', 'contact_number', 
                'expected_patients', 'images', 'video', 'service_in_camp'
            ];
            
            foreach ($columnsToDropIfExist as $column) {
                if (Schema::hasColumn('campaigns', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
