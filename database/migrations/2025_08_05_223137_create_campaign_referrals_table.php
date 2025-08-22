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
        Schema::create('campaign_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('referrer_user_id')->constrained('users')->onDelete('cascade'); // Who referred this user
            $table->decimal('per_campaign_refer_cost', 10, 2)->default(0); // Cost per referral for this campaign
            $table->string('referral_code', 50); // Unique referral code for tracking
            $table->timestamp('registration_completed_at')->nullable(); // When the referred user completed registration
            $table->enum('status', ['pending', 'completed', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'campaign_id']);
            $table->index(['referrer_user_id', 'campaign_id']);
            $table->index('referral_code');
            $table->unique(['user_id', 'campaign_id']); // One referral per user per campaign
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_referrals');
    }
};
