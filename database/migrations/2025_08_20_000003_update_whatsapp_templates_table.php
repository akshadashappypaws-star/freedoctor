<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            // Add new columns only if they don't exist
            if (!Schema::hasColumn('whatsapp_templates', 'whatsapp_id')) {
                $table->string('whatsapp_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'language')) {
                $table->string('language')->default('en')->after('content');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'category')) {
                $table->string('category')->default('UTILITY')->after('language');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'components')) {
                $table->json('components')->nullable()->after('category');
            }
            if (!Schema::hasColumn('whatsapp_templates', 'status')) {
                $table->string('status')->default('pending')->after('components');
            }
        });
        
        // Drop old columns if they exist
        if (Schema::hasColumn('whatsapp_templates', 'variables')) {
            Schema::table('whatsapp_templates', function (Blueprint $table) {
                $table->dropColumn('variables');
            });
        }
    }

    public function down()
    {
        // Add back variables column if it doesn't exist
        if (!Schema::hasColumn('whatsapp_templates', 'variables')) {
            Schema::table('whatsapp_templates', function (Blueprint $table) {
                $table->json('variables')->nullable();
            });
        }
        
        // Drop columns if they exist
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $columnsToCheck = [
                'whatsapp_id',
                'language',
                'category',
                'components',
                'status'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('whatsapp_templates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
