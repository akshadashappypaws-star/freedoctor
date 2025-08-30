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
            // Add user relationship
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // Add phone_number column (rename from phone)
            $table->string('phone_number')->nullable()->after('phone');
            
            // Add camp-related columns
            $table->string('camp_request_type')->nullable()->after('organization_type');
            $table->unsignedBigInteger('specialty_id')->nullable()->after('camp_request_type');
            $table->date('date_from')->nullable()->after('specialty_id');
            $table->date('date_to')->nullable()->after('date_from');
            $table->integer('number_of_people')->nullable()->after('date_to');
            $table->text('location')->nullable()->after('number_of_people');
            $table->text('description')->nullable()->after('location');
            $table->unsignedBigInteger('hired_doctor_id')->nullable()->after('description');
            
            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('set null');
            $table->foreign('hired_doctor_id')->references('id')->on('doctors')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('user_id');
            $table->index('specialty_id');
            $table->index('hired_doctor_id');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_organization_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['specialty_id']);
            $table->dropForeign(['hired_doctor_id']);
            
            $table->dropIndex(['user_id']);
            $table->dropIndex(['specialty_id']);
            $table->dropIndex(['hired_doctor_id']);
            $table->dropIndex(['user_id', 'status']);
            
            $table->dropColumn([
                'user_id',
                'phone_number',
                'camp_request_type',
                'specialty_id',
                'date_from',
                'date_to',
                'number_of_people',
                'location',
                'description',
                'hired_doctor_id'
            ]);
        });
    }
};
