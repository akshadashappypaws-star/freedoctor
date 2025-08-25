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
        Schema::create('whatsapp_template_table_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('whatsapp_templates')->onDelete('cascade');
            $table->json('linked_tables'); // Array of table names to link with
            $table->string('trigger_event')->default('manual'); // manual, auto_daily, auto_weekly, etc.
            $table->integer('delay_minutes')->default(0); // Delay before sending
            $table->integer('priority')->default(1); // Priority level for queue
            $table->json('table_fields')->nullable(); // Fields to map from each table
            $table->json('row_limits')->nullable(); // Row limits for each table
            $table->json('sort_orders')->nullable(); // Sort order for each table
            $table->json('filters')->nullable(); // Filters for each table
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('total_sent')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_template_table_links');
    }
};
