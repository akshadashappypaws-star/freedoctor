<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            // Add new columns
            $table->string('whatsapp_id')->nullable()->after('id');
            $table->string('language')->default('en')->after('content');
            $table->string('category')->default('UTILITY')->after('language');
            $table->json('components')->nullable()->after('category');
            $table->string('status')->default('pending')->after('components');
            
            // Drop old columns
            $table->dropColumn('variables');
        });
    }

    public function down()
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->json('variables')->nullable();
            
            $table->dropColumn([
                'whatsapp_id',
                'language',
                'category',
                'components',
                'status'
            ]);
        });
    }
};
