<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutStorySection;
use Illuminate\Http\Request;

class AboutStorySectionController extends Controller
{
    public function show()
    {
        return response()->json([
            'success' => true,
            'data' => AboutStorySection::query()->first(),
        ]);
    }

    public function adminShow()
    {
        return response()->json([
            'success' => true,
            'data' => AboutStorySection::query()->first(),
        ]);
    }

    public function upsert(Request $request)
    {
        $validated = $request->validate([
            'title_lead' => 'nullable|string|max:120',
            'title_accent' => 'nullable|string|max:120',
            'paragraph_one' => 'nullable|string',
            'paragraph_two' => 'nullable|string',
            'experience_value' => 'nullable|string|max:80',
            'experience_label' => 'nullable|string|max:120',
            'travelers_value' => 'nullable|string|max:80',
            'travelers_label' => 'nullable|string|max:120',
            'since_text' => 'nullable|string|max:120',
            'pioneering_text' => 'nullable|string|max:120',
            'image_url' => 'nullable|string|max:2048',
        ]);

        $section = AboutStorySection::query()->first();

        if (! $section) {
            $section = AboutStorySection::create($validated);
        } else {
            $section->update($validated);
        }

        return response()->json([
            'success' => true,
            'data' => $section->fresh(),
        ]);
    }
}
