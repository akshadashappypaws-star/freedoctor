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
        // First, backup current data if needed (data that looks like it should be in business_organization_requests)
        // Then restructure business_requests to match the BusinessRequest model
        
        Schema::dropIfExists('business_requests');
        
        // Recreate business_requests table with correct structure for doctor applications
        Schema::create('business_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_organization_request_id');
            $table->unsignedBigInteger('doctor_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('application_message')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('business_organization_request_id')->references('id')->on('business_organization_requests')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            
            // Indexes for better performance (with custom shorter names)
            $table->index(['business_organization_request_id', 'doctor_id'], 'br_org_doctor_idx');
            $table->index('status', 'br_status_idx');
            $table->index('applied_at', 'br_applied_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_requests');
        
        // Restore original table structure (current mismatched structure)
        Schema::create('business_requests', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('email');
            $table->text('business_description');
            $table->string('business_type');
            $table->decimal('requested_budget', 10, 2);
            $table->string('status');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }
};
