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
            // Add doctor_name column (model expects this instead of name)
            $table->string('doctor_name')->nullable()->after('name');
            
            // Add missing basic info columns
            $table->string('location')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('location');
            $table->unsignedBigInteger('specialty_id')->nullable()->after('gender');
            $table->string('hospital_name')->nullable()->after('specialty_id');
            $table->text('description')->nullable()->after('qualification');
            $table->string('intro_video')->nullable()->after('description');
            $table->string('image')->nullable()->after('intro_video');
            
            // Add verification and status columns
            $table->boolean('phone_verified')->default(false)->after('phone');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            $table->boolean('approved_by_admin')->default(false)->after('status');
            
            // Add payment-related columns
            $table->boolean('payment_completed')->default(false)->after('approved_by_admin');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_completed');
            
            // Add banking information columns
            $table->string('bank_name')->nullable()->after('payment_completed_at');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('account_holder_name')->nullable()->after('ifsc_code');
            $table->decimal('withdrawn_amount', 10, 2)->default(0)->after('account_holder_name');
            
            // Add foreign key constraint for specialty_id
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('specialty_id');
            $table->index(['status', 'approved_by_admin']);
            $table->index('payment_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropIndex(['specialty_id']);
            $table->dropIndex(['status', 'approved_by_admin']);
            $table->dropIndex(['payment_completed']);
            
            $table->dropColumn([
                'doctor_name',
                'location',
                'gender',
                'specialty_id',
                'hospital_name',
                'description',
                'intro_video',
                'image',
                'phone_verified',
                'email_verified_at',
                'approved_by_admin',
                'payment_completed',
                'payment_completed_at',
                'bank_name',
                'account_number',
                'ifsc_code',
                'account_holder_name',
                'withdrawn_amount'
            ]);
        });
    }
};
