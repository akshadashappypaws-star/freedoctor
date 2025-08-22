<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_bulk_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('whatsapp_templates')->onDelete('set null');
            $table->text('recipients'); // JSON array of phone numbers
            $table->text('parameters')->nullable(); // JSON array of parameter values
            $table->boolean('is_scheduled')->default(false);
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_bulk_messages');
    }
};
