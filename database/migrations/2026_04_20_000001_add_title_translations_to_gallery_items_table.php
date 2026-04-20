<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('title_ko')->nullable()->after('title_en');
            $table->string('title_zh')->nullable()->after('title_ko');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'title_ko', 'title_zh']);
        });
    }
};
