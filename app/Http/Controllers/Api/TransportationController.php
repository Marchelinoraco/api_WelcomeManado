<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransportationController extends Controller
{
    public function index(Request $request)
    {
        $query = Transportation::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->has('available')) {
            $available = filter_var($request->get('available'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (! is_null($available)) {
                $query->where('available', $available);
            }
        }

        $perPage = $request->integer('per_page', 15);
        $items = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function show($id)
    {
        $item = Transportation::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Transportation not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'available' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('transportations', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $item = Transportation::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => (int) $request->price,
            'available' => (bool) ($request->available ?? true),
            'image_url' => $imageUrl,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
        ]);

        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = Transportation::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Transportation not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'price' => 'required|integer|min:0',
            'available' => 'nullable|boolean',
            'image' => 'nullable|image|max:5120',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
        ]);

        $imageUrl = $item->image_url;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($item->image_url) {
                $parsed = parse_url($item->image_url, PHP_URL_PATH);
                $storagePath = ltrim(str_replace('/storage/', '', $parsed), '/');
                Storage::disk('public')->delete($storagePath);
            }
            $path = $request->file('image')->store('transportations', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $item->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => (int) $request->price,
            'available' => (bool) ($request->available ?? $item->available),
            'image_url' => $imageUrl,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
        ]);

        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = Transportation::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Transportation not found'], 404);
        }

        $item->delete();
        return response()->json(['success' => true]);
    }
}
