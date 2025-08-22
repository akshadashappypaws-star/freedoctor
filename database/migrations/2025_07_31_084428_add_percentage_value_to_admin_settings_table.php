<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('admin_settings', function (Blueprint $table) {
        $table->decimal('percentage_value', 12, 5)->nullable()->change();
    });
}

public function down()
{
    Schema::table('admin_settings', function (Blueprint $table) {
        $table->decimal('percentage_value', 8, 2)->nullable()->change();
    });
}

};
