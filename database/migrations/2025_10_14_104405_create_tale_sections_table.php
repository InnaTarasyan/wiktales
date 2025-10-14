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
        Schema::create('tale_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tale_id')->constrained('tales')->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->string('title')->nullable();
            $table->string('anchor')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tale_sections');
    }
};
