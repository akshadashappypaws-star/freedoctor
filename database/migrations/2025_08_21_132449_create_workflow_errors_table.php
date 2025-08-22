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
        Schema::create('workflow_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->string('machine_type'); // ai, function, datatable, template, visualization
            $table->string('error_type');
            $table->text('error_message');
            $table->json('error_data')->nullable();
            $table->string('severity')->default('error'); // error, warning, critical
            $table->boolean('resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['machine_type', 'error_type']);
            $table->index(['severity', 'resolved']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_errors');
    }
};
