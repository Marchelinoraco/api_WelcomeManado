<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_story_sections', function (Blueprint $table) {
            $table->string('title_lead_en')->nullable()->after('title_lead');
            $table->string('title_lead_ko')->nullable()->after('title_lead_en');
            $table->string('title_lead_zh')->nullable()->after('title_lead_ko');

            $table->string('title_accent_en')->nullable()->after('title_accent');
            $table->string('title_accent_ko')->nullable()->after('title_accent_en');
            $table->string('title_accent_zh')->nullable()->after('title_accent_ko');

            $table->longText('paragraph_one_en')->nullable()->after('paragraph_one');
            $table->longText('paragraph_one_ko')->nullable()->after('paragraph_one_en');
            $table->longText('paragraph_one_zh')->nullable()->after('paragraph_one_ko');

            $table->longText('paragraph_two_en')->nullable()->after('paragraph_two');
            $table->longText('paragraph_two_ko')->nullable()->after('paragraph_two_en');
            $table->longText('paragraph_two_zh')->nullable()->after('paragraph_two_ko');

            $table->string('experience_label_en')->nullable()->after('experience_label');
            $table->string('experience_label_ko')->nullable()->after('experience_label_en');
            $table->string('experience_label_zh')->nullable()->after('experience_label_ko');

            $table->string('travelers_label_en')->nullable()->after('travelers_label');
            $table->string('travelers_label_ko')->nullable()->after('travelers_label_en');
            $table->string('travelers_label_zh')->nullable()->after('travelers_label_ko');

            $table->string('since_text_en')->nullable()->after('since_text');
            $table->string('since_text_ko')->nullable()->after('since_text_en');
            $table->string('since_text_zh')->nullable()->after('since_text_ko');

            $table->string('pioneering_text_en')->nullable()->after('pioneering_text');
            $table->string('pioneering_text_ko')->nullable()->after('pioneering_text_en');
            $table->string('pioneering_text_zh')->nullable()->after('pioneering_text_ko');
        });
    }

    public function down(): void
    {
        Schema::table('about_story_sections', function (Blueprint $table) {
            $table->dropColumn([
                'title_lead_en', 'title_lead_ko', 'title_lead_zh',
                'title_accent_en', 'title_accent_ko', 'title_accent_zh',
                'paragraph_one_en', 'paragraph_one_ko', 'paragraph_one_zh',
                'paragraph_two_en', 'paragraph_two_ko', 'paragraph_two_zh',
                'experience_label_en', 'experience_label_ko', 'experience_label_zh',
                'travelers_label_en', 'travelers_label_ko', 'travelers_label_zh',
                'since_text_en', 'since_text_ko', 'since_text_zh',
                'pioneering_text_en', 'pioneering_text_ko', 'pioneering_text_zh',
            ]);
        });
    }
};
