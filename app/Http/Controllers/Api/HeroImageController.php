<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroImageController extends Controller
{
    private function deleteStoredImage(?string $imagePath): void
    {
        $parsed = parse_url((string) $imagePath, PHP_URL_PATH);
        $publicPrefix = '/storage/';

        if (is_string($parsed) && str_starts_with($parsed, $publicPrefix)) {
            $storagePath = substr($parsed, strlen($publicPrefix));
            Storage::disk('public')->delete($storagePath);
        }
    }

    public function publicIndex()
    {
        $items = HeroImage::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Hero Images retrieved successfully',
            'data' => $items,
        ]);
    }

    public function index(Request $request)
    {
        $query = HeroImage::query();

        if ($request->has('active')) {
            $query->where('is_active', filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN));
        }

        $query
            ->orderBy('order')
            ->orderByDesc('created_at');

        $perPage = (int) $request->input('per_page', 0);
        if ($perPage > 0) {
            $perPage = max(1, min(60, $perPage));
            $items = $query->paginate($perPage);
        } else {
            $items = $query->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'List Hero Images',
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('hero/images', 'public');
        $imageUrl = url(Storage::url($path));

        $item = HeroImage::create([
            'image_url' => $imageUrl,
            'order' => (int) ($validated['order'] ?? 0),
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hero image created successfully',
            'data' => $item,
        ], 201);
    }

    public function show($id)
    {
        $item = HeroImage::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Hero image not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = HeroImage::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Hero image not found'], 404);
        }

        $validated = $request->validate([
            'image' => 'nullable|image|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $update = [
            'order' => (int) ($validated['order'] ?? 0),
        ];

        if (array_key_exists('is_active', $validated)) {
            $update['is_active'] = (bool) $validated['is_active'];
        }

        if ($request->hasFile('image')) {
            $previousImage = $item->image_url;
            $path = $request->file('image')->store('hero/images', 'public');
            $update['image_url'] = url(Storage::url($path));
            if ($previousImage) {
                $this->deleteStoredImage($previousImage);
            }
        }

        $item->update($update);

        return response()->json([
            'success' => true,
            'message' => 'Hero image updated successfully',
            'data' => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = HeroImage::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Hero image not found'], 404);
        }

        if ($item->image_url) {
            $this->deleteStoredImage($item->image_url);
        }
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Hero image deleted successfully']);
    }
}
