<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_story_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title_lead')->nullable();
            $table->string('title_accent')->nullable();
            $table->longText('paragraph_one')->nullable();
            $table->longText('paragraph_two')->nullable();
            $table->string('experience_value')->nullable();
            $table->string('experience_label')->nullable();
            $table->string('travelers_value')->nullable();
            $table->string('travelers_label')->nullable();
            $table->string('since_text')->nullable();
            $table->string('pioneering_text')->nullable();
            $table->string('image_url', 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_story_sections');
    }
};
