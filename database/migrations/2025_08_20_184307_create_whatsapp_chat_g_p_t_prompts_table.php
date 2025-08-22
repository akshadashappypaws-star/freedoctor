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
        Schema::create('whatsapp_chat_g_p_t_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Prompt name/title
            $table->text('prompt'); // The actual prompt text
            $table->text('description')->nullable(); // Description of what this prompt does
            $table->string('category')->default('general'); // Category: medical, appointment, general, etc.
            $table->boolean('is_active')->default(true); // Whether this prompt is active
            $table->integer('usage_count')->default(0); // How many times this prompt has been used
            $table->json('variables')->nullable(); // JSON array of variables that can be replaced in the prompt
            $table->text('example_response')->nullable(); // Example of expected response
            $table->integer('priority')->default(0); // Priority order for prompt selection
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'category']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_g_p_t_prompts');
    }
};
