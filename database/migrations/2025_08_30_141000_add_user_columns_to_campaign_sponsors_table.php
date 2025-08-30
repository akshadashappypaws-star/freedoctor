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
        Schema::table('campaign_sponsors', function (Blueprint $table) {
            // Add user relationship and user details
            $table->unsignedBigInteger('user_id')->nullable()->after('campaign_id');
            $table->string('name')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('name');
            $table->string('phone_number')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone_number');
            $table->text('message')->nullable()->after('address');
            
            // Payment processing columns
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('payment_id')->nullable()->after('payment_status');
            $table->json('payment_details')->nullable()->after('payment_id');
            $table->timestamp('payment_date')->nullable()->after('payment_details');
            
            // Add foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('user_id');
            $table->index(['campaign_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_sponsors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['campaign_id', 'user_id']);
            
            $table->dropColumn([
                'user_id',
                'name',
                'email',
                'phone_number',
                'address',
                'message',
                'payment_method',
                'payment_status',
                'payment_id',
                'payment_details',
                'payment_date'
            ]);
        });
    }
};
