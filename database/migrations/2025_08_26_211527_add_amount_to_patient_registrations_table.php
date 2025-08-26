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
            $table->decimal('amount', 10, 2)->default(0)->after('status');
            $table->string('payment_status')->default('pending')->after('amount');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('transaction_id')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_registrations', function (Blueprint $table) {
            $table->dropColumn(['amount', 'payment_status', 'payment_method', 'transaction_id']);
        });
    }
};
