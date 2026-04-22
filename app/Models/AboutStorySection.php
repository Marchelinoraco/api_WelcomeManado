<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutStorySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_lead',
        'title_accent',
        'paragraph_one',
        'paragraph_two',
        'experience_value',
        'experience_label',
        'travelers_value',
        'travelers_label',
        'since_text',
        'pioneering_text',
        'image_url',
    ];
}
