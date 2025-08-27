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
        Schema::table('workflows', function (Blueprint $table) {
            if (!Schema::hasColumn('workflows', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('id');
                $table->index('whatsapp_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflows', function (Blueprint $table) {
            if (Schema::hasColumn('workflows', 'whatsapp_number')) {
                $table->dropIndex(['whatsapp_number']);
                $table->dropColumn('whatsapp_number');
            }
        });
    }
};
