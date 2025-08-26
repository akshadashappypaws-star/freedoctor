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
        Schema::create('campaigns', function (Blueprint $table) {
            
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->decimal("budget", 10, 2);
            $table->unsignedBigInteger("category_id");
            $table->string("target_audience");
            $table->json("requirements")->nullable();
            $table->string("status")->default("active");
            $table->date("start_date");
            $table->date("end_date");
            $table->string("thumbnail")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table->string("location")->nullable();
            $table->timestamps();
            $table->foreign("category_id")->references("id")->on("categories")->onDelete("cascade");
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
