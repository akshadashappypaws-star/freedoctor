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
        Schema::table('patient_payments', function (Blueprint $table) {
            // Add user-based columns
            if (!Schema::hasColumn('patient_payments', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('patient_payments', 'patient_registration_id')) {
                $table->unsignedBigInteger('patient_registration_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('patient_payments', 'campaign_id')) {
                $table->unsignedBigInteger('campaign_id')->nullable()->after('patient_registration_id');
            }
            
            // Add payment system columns
            if (!Schema::hasColumn('patient_payments', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('campaign_id');
            }
            if (!Schema::hasColumn('patient_payments', 'order_id')) {
                $table->string('order_id')->nullable()->after('payment_id');
            }
            if (!Schema::hasColumn('patient_payments', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('order_id');
            }
            if (!Schema::hasColumn('patient_payments', 'razorpay_payout_id')) {
                $table->string('razorpay_payout_id')->nullable()->after('payment_status');
            }
            
            // Add financial columns
            if (!Schema::hasColumn('patient_payments', 'admin_commission')) {
                $table->decimal('admin_commission', 10, 2)->default(0)->after('razorpay_payout_id');
            }
            if (!Schema::hasColumn('patient_payments', 'doctor_amount')) {
                $table->decimal('doctor_amount', 10, 2)->default(0)->after('admin_commission');
            }
            
            // Add additional detail columns
            if (!Schema::hasColumn('patient_payments', 'bank_details')) {
                $table->json('bank_details')->nullable()->after('doctor_amount');
            }
            if (!Schema::hasColumn('patient_payments', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('bank_details');
            }
            if (!Schema::hasColumn('patient_payments', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('receipt_number');
            }
            if (!Schema::hasColumn('patient_payments', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('patient_payments', 'failure_reason')) {
                $table->text('failure_reason')->nullable()->after('processed_at');
            }
            
            // Make patient_phone nullable for user-based system
            $table->string('patient_phone')->nullable()->change();
        });
        
        // Add foreign key constraints in a separate schema call
        Schema::table('patient_payments', function (Blueprint $table) {
            // Check if foreign keys don't exist before adding them
            $foreignKeys = collect(DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'patient_payments' 
                AND CONSTRAINT_NAME != 'PRIMARY'
            ", [config('database.connections.mysql.database')]))->pluck('CONSTRAINT_NAME');
            
            if (!$foreignKeys->contains('patient_payments_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
            if (!$foreignKeys->contains('patient_payments_patient_registration_id_foreign')) {
                $table->foreign('patient_registration_id')->references('id')->on('patient_registrations')->onDelete('cascade');
            }
            if (!$foreignKeys->contains('patient_payments_campaign_id_foreign')) {
                $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_payments', function (Blueprint $table) {
            // Remove foreign key constraints first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['patient_registration_id']);
            $table->dropForeign(['campaign_id']);
            
            // Drop added columns
            $table->dropColumn([
                'user_id',
                'patient_registration_id',
                'campaign_id',
                'payment_id',
                'order_id',
                'payment_status',
                'razorpay_payout_id',
                'admin_commission',
                'doctor_amount',
                'bank_details',
                'receipt_number',
                'payment_date',
                'processed_at',
                'failure_reason'
            ]);
            
            // Make patient_phone required again
            $table->string('patient_phone')->nullable(false)->change();
        });
    }
};
