<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorMessagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('type')->nullable(); // e.g., registration, payment, alert
            $table->text('message');
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_messages');
    }
}
