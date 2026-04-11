<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_info_items', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('category_key')->nullable();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('title_ko')->nullable();
            $table->string('title_zh')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ko')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('image_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['type', 'category_key', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_info_items');
    }
};
