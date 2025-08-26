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
            $table->unsignedBigInteger("template_id");
            $table->string("table_name");
            $table->string("field_mapping");
            $table->json("conditions")->nullable();
            $table->timestamps();
            $table->foreign("template_id")->references("id")->on("whatsapp_templates")->onDelete("cascade");
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
