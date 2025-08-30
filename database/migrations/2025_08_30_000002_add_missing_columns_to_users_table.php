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
            // Basic user information
            $table->string('username')->nullable()->after('name');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('password');
            $table->string('gender')->nullable()->after('status');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            
            // Referral system
            $table->string('your_referral_id')->unique()->nullable()->after('emergency_contact_phone');
            $table->string('referred_by')->nullable()->after('your_referral_id');
            $table->timestamp('referral_completed_at')->nullable()->after('referred_by');
            
            // Banking and earnings
            $table->string('bank_account_number')->nullable()->after('referral_completed_at');
            $table->string('bank_ifsc_code')->nullable()->after('bank_account_number');
            $table->string('bank_name')->nullable()->after('bank_ifsc_code');
            $table->string('account_holder_name')->nullable()->after('bank_name');
            $table->string('razorpay_contact_id')->nullable()->after('account_holder_name');
            $table->string('razorpay_fund_account_id')->nullable()->after('razorpay_contact_id');
            $table->decimal('total_earnings', 10, 2)->default(0)->after('razorpay_fund_account_id');
            $table->decimal('withdrawn_amount', 10, 2)->default(0)->after('total_earnings');
            $table->decimal('available_balance', 10, 2)->default(0)->after('withdrawn_amount');
            
            // Location tracking
            $table->decimal('latitude', 10, 8)->nullable()->after('available_balance');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('location_address')->nullable()->after('longitude');
            $table->string('location_source')->nullable()->after('location_address');
            $table->timestamp('location_updated_at')->nullable()->after('location_source');
            $table->boolean('location_permission_granted')->default(false)->after('location_updated_at');
            $table->ipAddress('ip_address')->nullable()->after('location_permission_granted');
            
            // Social login
            $table->string('google_id')->nullable()->after('ip_address');
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('profile_picture')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'status', 'gender', 'date_of_birth', 'emergency_contact_name', 'emergency_contact_phone',
                'your_referral_id', 'referred_by', 'referral_completed_at',
                'bank_account_number', 'bank_ifsc_code', 'bank_name', 'account_holder_name',
                'razorpay_contact_id', 'razorpay_fund_account_id', 'total_earnings', 'withdrawn_amount', 'available_balance',
                'latitude', 'longitude', 'location_address', 'location_source', 'location_updated_at', 'location_permission_granted', 'ip_address',
                'google_id', 'avatar', 'profile_picture'
            ]);
        });
    }
};
