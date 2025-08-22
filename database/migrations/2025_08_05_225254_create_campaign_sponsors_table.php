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
        Schema::create('campaign_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone_number');
            $table->text('address');
            $table->text('message')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['campaign_id', 'payment_status']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_sponsors');
    }
};
