<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStorySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_lead', 'title_lead_en', 'title_lead_ko', 'title_lead_zh',
        'title_accent', 'title_accent_en', 'title_accent_ko', 'title_accent_zh',
        'paragraph_one', 'paragraph_one_en', 'paragraph_one_ko', 'paragraph_one_zh',
        'paragraph_two', 'paragraph_two_en', 'paragraph_two_ko', 'paragraph_two_zh',
        'experience_value',
        'experience_label', 'experience_label_en', 'experience_label_ko', 'experience_label_zh',
        'travelers_value',
        'travelers_label', 'travelers_label_en', 'travelers_label_ko', 'travelers_label_zh',
        'since_text', 'since_text_en', 'since_text_ko', 'since_text_zh',
        'pioneering_text', 'pioneering_text_en', 'pioneering_text_ko', 'pioneering_text_zh',
        'image_url',
    ];
}
