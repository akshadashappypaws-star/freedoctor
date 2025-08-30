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
        Schema::table('patient_registrations', function (Blueprint $table) {
            // Add user relationship
            if (!Schema::hasColumn('patient_registrations', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            
            // Add campaign relationship  
            if (!Schema::hasColumn('patient_registrations', 'campaign_id')) {
                $table->unsignedBigInteger('campaign_id')->nullable()->after('user_id');
            }
            
            // Add additional patient fields
            if (!Schema::hasColumn('patient_registrations', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('campaign_id');
            }
            
            if (!Schema::hasColumn('patient_registrations', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('patient_name');
            }
            
            if (!Schema::hasColumn('patient_registrations', 'description')) {
                $table->text('description')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('patient_registrations', 'registration_reason')) {
                $table->text('registration_reason')->nullable()->after('description');
            }
            
            // Add payment related fields
            if (!Schema::hasColumn('patient_registrations', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('transaction_id');
            }
            
            if (!Schema::hasColumn('patient_registrations', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('payment_id');
            }
            
            if (!Schema::hasColumn('patient_registrations', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_date');
            }
        });
        
        // Add foreign key constraints in separate schema call
        Schema::table('patient_registrations', function (Blueprint $table) {
            // Check if foreign keys don't exist before adding them
            $foreignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'patient_registrations' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ", [config('database.connections.mysql.database')]))->pluck('CONSTRAINT_NAME');
            
            if (!$foreignKeys->contains('patient_registrations_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$foreignKeys->contains('patient_registrations_campaign_id_foreign')) {
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_registrations', function (Blueprint $table) {
            // Remove foreign key constraints first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['campaign_id']);
            
            // Drop added columns
            $table->dropColumn([
                'user_id',
                'campaign_id',
                'patient_name',
                'phone_number',
                'description',
                'registration_reason',
                'payment_id',
                'payment_date',
                'payment_amount'
            ]);
        });
    }
};
