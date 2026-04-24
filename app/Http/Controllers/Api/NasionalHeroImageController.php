<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NasionalHeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NasionalHeroImageController extends Controller
{
    private function deleteStoredImage(?string $imagePath): void
    {
        $parsed = parse_url((string) $imagePath, PHP_URL_PATH);
        $publicPrefix = '/storage/';
        if (is_string($parsed) && str_starts_with($parsed, $publicPrefix)) {
            Storage::disk('public')->delete(substr($parsed, strlen($publicPrefix)));
        }
    }

    public function publicIndex()
    {
        $items = NasionalHeroImage::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    public function index(Request $request)
    {
        $items = NasionalHeroImage::orderBy('order')->orderByDesc('created_at')->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('nasional/hero', 'public');
        $item = NasionalHeroImage::create([
            'image_url' => url(Storage::url($path)),
            'order' => (int) ($request->order ?? 0),
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return response()->json(['success' => true, 'data' => $item], 201);
    }

    public function update(Request $request, $id)
    {
        $item = NasionalHeroImage::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $request->validate([
            'image' => 'nullable|image|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $update = ['order' => (int) ($request->order ?? $item->order)];
        if ($request->has('is_active')) {
            $update['is_active'] = (bool) $request->is_active;
        }
        if ($request->hasFile('image')) {
            $this->deleteStoredImage($item->image_url);
            $path = $request->file('image')->store('nasional/hero', 'public');
            $update['image_url'] = url(Storage::url($path));
        }

        $item->update($update);
        return response()->json(['success' => true, 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = NasionalHeroImage::find($id);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        $this->deleteStoredImage($item->image_url);
        $item->delete();
        return response()->json(['success' => true]);
    }
}
