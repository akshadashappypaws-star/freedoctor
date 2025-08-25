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
        if (!Schema::hasTable('workflow_machine_configs')) {
            Schema::create('workflow_machine_configs', function (Blueprint $table) {
                $table->id();
                $table->string('machine_type'); // ai, function, datatable, template, visualization
                $table->string('config_name');
                $table->longText('config_json'); // Machine-specific configuration
                $table->boolean('is_active')->default(true);
                $table->integer('priority')->default(0); // For ordering
                $table->string('version')->default('1.0');
                $table->timestamps();

                $table->unique(['machine_type', 'config_name']);
                $table->index(['machine_type', 'is_active']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_machine_configs');
    }
};
