<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_story_sections', function (Blueprint $table) {
            $table->string('experience_value_en', 80)->nullable()->after('experience_value');
            $table->string('experience_value_ko', 80)->nullable()->after('experience_value_en');
            $table->string('experience_value_zh', 80)->nullable()->after('experience_value_ko');
            $table->string('travelers_value_en', 80)->nullable()->after('travelers_value');
            $table->string('travelers_value_ko', 80)->nullable()->after('travelers_value_en');
            $table->string('travelers_value_zh', 80)->nullable()->after('travelers_value_ko');
        });
    }

    public function down(): void
    {
        Schema::table('about_story_sections', function (Blueprint $table) {
            $table->dropColumn([
                'experience_value_en', 'experience_value_ko', 'experience_value_zh',
                'travelers_value_en', 'travelers_value_ko', 'travelers_value_zh',
            ]);
        });
    }
};
