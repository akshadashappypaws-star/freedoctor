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
        Schema::create('whatsapp_user_behavior', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->index(); // User phone number
            $table->string('engagement_type'); // Type of engagement (high, medium, low, etc.)
            $table->integer('total_messages')->default(0); // Total messages sent by user
            $table->decimal('avg_response_time', 8, 2)->default(0); // Average response time in minutes
            $table->integer('questions_asked')->default(0); // Number of questions asked
            $table->integer('appointments_requested')->default(0); // Number of appointments requested
            $table->timestamp('last_interaction_at')->nullable(); // Last interaction timestamp
            $table->json('behavior_data')->nullable(); // Additional behavior data in JSON format
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['phone', 'engagement_type']);
            $table->index('last_interaction_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_user_behavior');
    }
};
