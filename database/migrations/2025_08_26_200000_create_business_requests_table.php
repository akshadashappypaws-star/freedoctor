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
        Schema::create('business_requests', function (Blueprint $table) {
            
            $table->id();
            $table->string("business_name");
            $table->string("contact_person");
            $table->string("phone");
            $table->string("email");
            $table->text("business_description");
            $table->string("business_type");
            $table->decimal("requested_budget", 10, 2)->nullable();
            $table->string("status")->default("pending");
            $table->text("admin_notes")->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_requests');
    }
};
