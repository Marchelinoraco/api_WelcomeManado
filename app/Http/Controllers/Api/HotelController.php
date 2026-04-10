<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query()->with('images');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        $hotels = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json(['success' => true, 'data' => $hotels]);
    }

    public function show($slug)
    {
        $hotel = ctype_digit((string) $slug)
            ? Hotel::with('images')->find((int) $slug)
            : Hotel::where('slug', $slug)->with('images')->first();
        if (! $hotel) {
            return response()->json(['success' => false, 'message' => 'Hotel not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $hotel]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'stars' => 'nullable|integer|min:0|max:5',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

        $hotel = Hotel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'location' => $request->location,
            'category' => $request->category,
            'stars' => $request->stars ?? 0,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('hotels', 'public');
                $url = url(Storage::url($path));
                $hotel->images()->create([
                    'image_path' => $url,
                    'is_primary' => $idx === 0,
                ]);
                if ($idx === 0) {
                    $hotel->primary_image = $url;
                }
            }
            $hotel->save();
        }

        return response()->json(['success' => true, 'data' => $hotel->load('images')], 201);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::find($id);
        if (! $hotel) {
            return response()->json(['success' => false, 'message' => 'Hotel not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:50',
            'stars' => 'nullable|integer|min:0|max:5',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

        $hotel->update([
            'name' => $request->name,
            'slug' => $hotel->slug ?? (Str::slug($request->name) . '-' . Str::random(6)),
            'location' => $request->location,
            'category' => $request->category,
            'stars' => $request->stars ?? 0,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            $existingCount = $hotel->images()->count();
            $newCount = count($request->file('images') ?? []);
            if ($existingCount + $newCount > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal 5 gambar per hotel',
                ], 422);
            }
            foreach ($request->file('images') as $file) {
                $path = $file->store('hotels', 'public');
                $url = url(Storage::url($path));
                $hotel->images()->create([
                    'image_path' => $url,
                    'is_primary' => false,
                ]);
            }
            if (! $hotel->primary_image) {
                $first = $hotel->images()->first();
                if ($first) {
                    $first->is_primary = true;
                    $first->save();
                    $hotel->primary_image = $first->image_path;
                    $hotel->save();
                }
            }
        }

        return response()->json(['success' => true, 'data' => $hotel->load('images')]);
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        if (! $hotel) {
            return response()->json(['success' => false, 'message' => 'Hotel not found'], 404);
        }
        foreach ($hotel->images as $img) {
            $parsed = parse_url($img->image_path, PHP_URL_PATH);
            $prefix = '/storage/';
            if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
                $rel = substr($parsed, strlen($prefix));
                Storage::disk('public')->delete($rel);
            }
        }
        $hotel->delete();
        return response()->json(['success' => true]);
    }
}
