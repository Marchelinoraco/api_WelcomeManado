<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelInfoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelInfoItemController extends Controller
{
    private function deleteStoredImage(?string $imageUrl): void
    {
        if (!$imageUrl) return;
        $parsed = parse_url($imageUrl, PHP_URL_PATH);
        $prefix = '/storage/';
        if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
            Storage::disk('public')->delete(substr($parsed, strlen($prefix)));
        }
    }

    private function isUploadedImage(?string $url): bool
    {
        if (!$url) return false;
        return str_contains($url, '/storage/travel-info/');
    }
    public function index(Request $request)
    {
        $query = TravelInfoItem::query();

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($categoryKey = $request->get('category_key')) {
            $query->where('category_key', $categoryKey);
        }

        if ($request->has('active')) {
            $active = filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (! is_null($active)) {
                $query->where('is_active', $active);
            }
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('title_ko', 'like', "%{$search}%")
                    ->orWhere('title_zh', 'like', "%{$search}%");
            });
        }

        $query->orderBy('sort_order')->orderByDesc('created_at');

        $perPage = (int) $request->input('per_page', 0);
        if ($perPage > 0) {
            $perPage = max(1, min(60, $perPage));
            $items = $query->paginate($perPage);
        } else {
            $items = $query->get();
        }

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:culture,food,shopping,guide',
            'category_key' => 'nullable|string|max:100|required_unless:type,culture,guide',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ko' => 'nullable|string|max:255',
            'title_zh' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'image_url' => 'nullable|string|max:2048',
            'image' => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('travel-info', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $item = TravelInfoItem::create([
            'type' => $validated['type'],
            'category_key' => $validated['category_key'] ?? null,
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,
            'title_ko' => $validated['title_ko'] ?? null,
            'title_zh' => $validated['title_zh'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_ko' => $validated['description_ko'] ?? null,
            'description_zh' => $validated['description_zh'] ?? null,
            'image_url' => $imageUrl,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
        ]);

        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function show($id)
    {
        $item = TravelInfoItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Travel info item not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = TravelInfoItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Travel info item not found'], 404);
        }

        $validated = $request->validate([
            'type' => 'required|string|in:culture,food,shopping,guide',
            'category_key' => 'nullable|string|max:100|required_unless:type,culture,guide',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ko' => 'nullable|string|max:255',
            'title_zh' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'image_url' => 'nullable|string|max:2048',
            'image' => 'nullable|image|max:5120',
            'remove_image' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imageUrl = $item->image_url;
        if ($request->hasFile('image')) {
            if ($this->isUploadedImage($item->image_url)) {
                $this->deleteStoredImage($item->image_url);
            }
            $path = $request->file('image')->store('travel-info', 'public');
            $imageUrl = url(Storage::url($path));
        } elseif ($request->input('remove_image') === '1') {
            if ($this->isUploadedImage($item->image_url)) {
                $this->deleteStoredImage($item->image_url);
            }
            $imageUrl = null;
        } elseif (array_key_exists('image_url', $validated)) {
            $imageUrl = $validated['image_url'] ?? null;
        }

        $update = [
            'type' => $validated['type'],
            'category_key' => $validated['category_key'] ?? null,
            'title' => $validated['title'],
            'title_en' => $validated['title_en'] ?? null,
            'title_ko' => $validated['title_ko'] ?? null,
            'title_zh' => $validated['title_zh'] ?? null,
            'description' => $validated['description'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_ko' => $validated['description_ko'] ?? null,
            'description_zh' => $validated['description_zh'] ?? null,
            'image_url' => $imageUrl,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if (array_key_exists('is_active', $validated)) {
            $update['is_active'] = (bool) $validated['is_active'];
        }

        $item->update($update);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = TravelInfoItem::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Travel info item not found'], 404);
        }

        $item->delete();
        return response()->json(['success' => true]);
    }
}
