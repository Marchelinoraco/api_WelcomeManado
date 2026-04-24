<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogHeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogHeroImageController extends Controller
{
    public function show()
    {
        $hero = BlogHeroImage::where('is_active', true)->latest()->first();
        return response()->json(['success' => true, 'data' => $hero]);
    }

    public function adminShow()
    {
        $hero = BlogHeroImage::latest()->first();
        return response()->json(['success' => true, 'data' => $hero]);
    }

    public function upsert(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        $hero = BlogHeroImage::latest()->first();

        $imageUrl = $hero?->image_url;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($hero?->image_url) {
                $parsed = parse_url($hero->image_url, PHP_URL_PATH);
                $prefix = '/storage/';
                if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
                    Storage::disk('public')->delete(substr($parsed, strlen($prefix)));
                }
            }
            $path = $request->file('image')->store('blog/hero', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $hero = BlogHeroImage::updateOrCreate(
            ['id' => $hero?->id ?? 0],
            [
                'image_url' => $imageUrl,
                'is_active' => $request->boolean('is_active', true),
            ]
        );

        return response()->json(['success' => true, 'data' => $hero]);
    }

    public function destroy()
    {
        $hero = BlogHeroImage::latest()->first();
        if ($hero) {
            if ($hero->image_url) {
                $parsed = parse_url($hero->image_url, PHP_URL_PATH);
                $prefix = '/storage/';
                if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
                    Storage::disk('public')->delete(substr($parsed, strlen($prefix)));
                }
            }
            $hero->delete();
        }
        return response()->json(['success' => true]);
    }
}
