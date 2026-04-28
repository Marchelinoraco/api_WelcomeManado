<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransportDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransportDestinationController extends Controller
{
    public function index(Request $request)
    {
        $query = TransportDestination::query();

        if ($request->has('active')) {
            $active = filter_var($request->get('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (! is_null($active)) {
                $query->where('is_active', $active);
            }
        }

        $items = $query->orderBy('sort_order')->orderBy('id')->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function show($id)
    {
        $item = TransportDestination::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'title_en'       => 'nullable|string|max:255',
            'title_ko'       => 'nullable|string|max:255',
            'title_zh'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'image'          => 'nullable|image|max:5120',
            'image_url'      => 'nullable|string|max:2048',
        ]);

        $imageUrl = $request->input('image_url');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('transport-destinations', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $item = TransportDestination::create([
            'title'          => $request->title,
            'title_en'       => $request->title_en,
            'title_ko'       => $request->title_ko,
            'title_zh'       => $request->title_zh,
            'description'    => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'sort_order'     => (int) ($request->sort_order ?? 0),
            'is_active'      => (bool) ($request->is_active ?? true),
            'image_url'      => $imageUrl,
        ]);

        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = TransportDestination::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $request->validate([
            'title'          => 'required|string|max:255',
            'title_en'       => 'nullable|string|max:255',
            'title_ko'       => 'nullable|string|max:255',
            'title_zh'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
            'image'          => 'nullable|image|max:5120',
            'image_url'      => 'nullable|string|max:2048',
            'remove_image'   => 'nullable|string',
        ]);

        $imageUrl = $item->image_url;

        if ($request->hasFile('image')) {
            // Delete old image if it was uploaded
            $this->deleteStoredImage($item->image_url);
            $path = $request->file('image')->store('transport-destinations', 'public');
            $imageUrl = url(Storage::url($path));
        } elseif ($request->input('remove_image') === '1') {
            $this->deleteStoredImage($item->image_url);
            $imageUrl = null;
        } elseif ($request->has('image_url')) {
            $imageUrl = $request->input('image_url') ?: null;
        }

        $item->update([
            'title'          => $request->title,
            'title_en'       => $request->title_en,
            'title_ko'       => $request->title_ko,
            'title_zh'       => $request->title_zh,
            'description'    => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'sort_order'     => (int) ($request->sort_order ?? 0),
            'is_active'      => (bool) ($request->is_active ?? $item->is_active),
            'image_url'      => $imageUrl,
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = TransportDestination::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        $this->deleteStoredImage($item->image_url);
        $item->delete();
        return response()->json(['success' => true]);
    }

    private function deleteStoredImage(?string $imageUrl): void
    {
        if (! $imageUrl) return;
        $parsed = parse_url($imageUrl, PHP_URL_PATH);
        $prefix = '/storage/';
        if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
            Storage::disk('public')->delete(substr($parsed, strlen($prefix)));
        }
    }
}
