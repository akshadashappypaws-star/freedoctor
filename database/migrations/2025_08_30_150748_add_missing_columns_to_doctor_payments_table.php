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
        Schema::table('doctor_payments', function (Blueprint $table) {
            // Add missing columns that the model expects
            $table->string('payment_id')->nullable()->after('amount');
            $table->string('order_id')->nullable()->after('payment_id');
            $table->string('receipt_number')->nullable()->after('order_id');
            $table->timestamp('payment_date')->nullable()->after('payment_details');
            
            // Add indexes for better performance
            $table->index('payment_id');
            $table->index('order_id');
            $table->index(['doctor_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_payments', function (Blueprint $table) {
            $table->dropIndex(['payment_id']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['doctor_id', 'payment_status']);
            
            $table->dropColumn([
                'payment_id',
                'order_id',
                'receipt_number',
                'payment_date'
            ]);
        });
    }
};
