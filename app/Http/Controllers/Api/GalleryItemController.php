<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryItemController extends Controller
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

    public function index(Request $request)
    {
        $query = GalleryItem::query();

        if ($request->has('active')) {
            $query->where('is_active', filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('has_youtube')) {
            $hasYoutube = filter_var($request->input('has_youtube'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($hasYoutube === true) {
                $query->whereNotNull('youtube_url')->where('youtube_url', '!=', '');
            } elseif ($hasYoutube === false) {
                $query->where(function ($q) {
                    $q->whereNull('youtube_url')->orWhere('youtube_url', '');
                });
            }
        }

        $query
            ->orderBy('sort_order')
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
            'message' => 'List Gallery Items',
            'data' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'video_name' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $hasImage = $request->hasFile('image');
        $hasYoutube = ! empty($validated['youtube_url'] ?? null);

        if (! $hasImage && ! $hasYoutube) {
            return response()->json([
                'success' => false,
                'message' => 'Image or YouTube URL is required.',
            ], 422);
        }

        $imageUrl = null;
        if ($hasImage) {
            $path = $request->file('image')->store('gallery/items', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $item = GalleryItem::create([
            'title' => $validated['title'],
            'image_path' => $imageUrl,
            'video_name' => $validated['video_name'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gallery item created successfully',
            'data' => $item,
        ], 201);
    }

    public function show($id)
    {
        $item = GalleryItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Gallery item not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = GalleryItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Gallery item not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:5120',
            'video_name' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $update = [
            'title' => $validated['title'],
            'video_name' => $validated['video_name'] ?? null,
            'youtube_url' => $validated['youtube_url'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if (array_key_exists('is_active', $validated)) {
            $update['is_active'] = (bool) $validated['is_active'];
        }

        if ($request->hasFile('image')) {
            $previousImage = $item->image_path;
            $path = $request->file('image')->store('gallery/items', 'public');
            $update['image_path'] = url(Storage::url($path));
            if ($previousImage) {
                $this->deleteStoredImage($previousImage);
            }
        }

        $finalYoutube = $update['youtube_url'] ?? null;
        $finalImage = array_key_exists('image_path', $update) ? $update['image_path'] : $item->image_path;
        if (empty($finalYoutube) && empty($finalImage)) {
            return response()->json([
                'success' => false,
                'message' => 'Image or YouTube URL is required.',
            ], 422);
        }

        $item->update($update);

        return response()->json([
            'success' => true,
            'message' => 'Gallery item updated successfully',
            'data' => $item,
        ]);
    }

    public function destroy($id)
    {
        $item = GalleryItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Gallery item not found'], 404);
        }

        if ($item->image_path) {
            $this->deleteStoredImage($item->image_path);
        }
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Gallery item deleted successfully']);
    }
}
