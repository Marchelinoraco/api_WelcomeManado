<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TravelInfoItem;
use Illuminate\Http\Request;

class TravelInfoItemController extends Controller
{
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
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

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
            'image_url' => $validated['image_url'] ?? null,
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
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

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
            'image_url' => $validated['image_url'] ?? null,
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
