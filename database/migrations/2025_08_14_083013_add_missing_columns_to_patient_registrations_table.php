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
        Schema::table('patient_registrations', function (Blueprint $table) {
            // Add missing columns for patient registration
            if (!Schema::hasColumn('patient_registrations', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('patient_registrations', 'age')) {
                $table->integer('age')->nullable()->after('email');
            }
            if (!Schema::hasColumn('patient_registrations', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('patient_registrations', 'medical_history')) {
                $table->text('medical_history')->nullable()->after('address');
            }
            if (!Schema::hasColumn('patient_registrations', 'registration_reason')) {
                $table->text('registration_reason')->nullable()->after('medical_history');
            }
            if (!Schema::hasColumn('patient_registrations', 'emergency_contact')) {
                $table->string('emergency_contact')->nullable()->after('registration_reason');
            }
            
            // Add payment-related columns
            if (!Schema::hasColumn('patient_registrations', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('status');
            }
            if (!Schema::hasColumn('patient_registrations', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('patient_registrations', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->default(0)->after('payment_id');
            }
            if (!Schema::hasColumn('patient_registrations', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('payment_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_registrations', function (Blueprint $table) {
            $columns = [
                'patient_name',
                'age', 
                'gender',
                'medical_history',
                'registration_reason',
                'emergency_contact',
                'payment_status',
                'payment_id',
                'payment_amount',
                'payment_date'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('patient_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
