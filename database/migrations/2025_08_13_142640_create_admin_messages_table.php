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
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable(); // Can be null for system messages
            $table->string('type')->default('info'); // info, success, warning, error, proposal, etc.
            $table->text('message');
            $table->json('data')->nullable(); // Additional data like doctor_id, proposal_id, etc.
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['admin_id', 'read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_messages');
    }
};
