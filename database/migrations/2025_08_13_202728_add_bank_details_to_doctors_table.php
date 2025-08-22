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
            $table->string('bank_name')->nullable()->after('status');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('account_holder_name')->nullable()->after('ifsc_code');
            $table->decimal('withdrawn_amount', 10, 2)->default(0)->after('account_holder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'ifsc_code', 'account_holder_name', 'withdrawn_amount']);
        });
    }
};
