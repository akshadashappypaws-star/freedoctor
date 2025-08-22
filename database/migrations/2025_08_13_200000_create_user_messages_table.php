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
        // Check if table exists first
        if (!Schema::hasTable('user_messages')) {
            Schema::create('user_messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type')->default('info'); // business_request, approval, rejection, etc.
                $table->text('message');
                $table->json('data')->nullable(); // Additional data like business_request_id, doctor_id, etc.
                $table->boolean('read')->default(false);
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'read']);
                $table->index('created_at');
            });
        } else {
            // Update existing table if needed
            Schema::table('user_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('user_messages', 'data')) {
                    $table->json('data')->nullable();
                }
                if (!Schema::hasColumn('user_messages', 'type')) {
                    $table->string('type')->default('info');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
