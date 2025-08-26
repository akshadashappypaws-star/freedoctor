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
            $table->unsignedBigInteger("campaign_id");
            $table->string("referrer_phone");
            $table->string("referee_phone");
            $table->decimal("commission", 8, 2)->default(0);
            $table->string("status")->default("pending");
            $table->timestamps();
            $table->foreign("campaign_id")->references("id")->on("campaigns")->onDelete("cascade");
        
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
