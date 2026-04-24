<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    private function normalizeFacilities(mixed $rawFacilities): array
    {
        if (is_string($rawFacilities)) {
            $rawFacilities = preg_split('/[\r\n,;|]+/', $rawFacilities) ?: [];
        } elseif (is_array($rawFacilities)) {
            $rawFacilities = collect($rawFacilities)->map(function ($item) {
                if (is_string($item)) {
                    return $item;
                }

                if (is_array($item)) {
                    return $item['name_id']
                        ?? $item['label_id']
                        ?? $item['name']
                        ?? $item['label']
                        ?? $item['title']
                        ?? null;
                }

                return null;
            })->all();
        } else {
            return [];
        }

        $seen = [];

        return collect($rawFacilities)
            ->map(fn ($item) => preg_replace('/\s+/', ' ', trim((string) $item)))
            ->filter()
            ->reject(function ($item) use (&$seen) {
                $key = mb_strtolower($item);
                if (in_array($key, $seen, true)) {
                    return true;
                }

                $seen[] = $key;
                return false;
            })
            ->values()
            ->all();
    }

    private function deleteStoredImage(?string $imagePath): void
    {
        $parsed = parse_url((string) $imagePath, PHP_URL_PATH);
        $prefix = '/storage/';

        if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
            $rel = substr($parsed, strlen($prefix));
            Storage::disk('public')->delete($rel);
        }
    }

    private function syncPrimaryImage(Hotel $hotel, ?int $primaryImageId = null): void
    {
        $images = $hotel->images()->orderBy('id')->get();

        if ($images->isEmpty()) {
            $hotel->primary_image = null;
            $hotel->save();
            return;
        }

        $primary = $primaryImageId
            ? $images->firstWhere('id', $primaryImageId)
            : $images->firstWhere('is_primary', true);

        if (! $primary) {
            $primary = $images->first();
        }

        $hotel->images()->where('id', '!=', $primary->id)->update(['is_primary' => false]);
        if (! $primary->is_primary) {
            $primary->is_primary = true;
            $primary->save();
        }

        $hotel->primary_image = $primary->image_path;
        $hotel->save();
    }

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

        $perPage = $request->integer('per_page', 60);
        $hotels = $query->orderBy('created_at', 'desc')->paginate($perPage);
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
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'facilities' => 'nullable',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'retain_image_ids' => 'nullable|array|max:5',
            'retain_image_ids.*' => 'integer',
            'primary_image_id' => 'nullable|integer',
            'slot_order' => 'nullable|array|max:5',
            'slot_order.*' => 'string',
        ]);

        $hotel = Hotel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'location' => $request->location,
            'category' => $request->category,
            'stars' => $request->stars ?? 0,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'facilities' => $this->normalizeFacilities($request->input('facilities')),
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
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'facilities' => 'nullable',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'retain_image_ids' => 'nullable|array|max:5',
            'retain_image_ids.*' => 'integer',
            'primary_image_id' => 'nullable|integer',
            'slot_order' => 'nullable|array|max:5',
            'slot_order.*' => 'string',
        ]);

        $hotel->update([
            'name' => $request->name,
            'slug' => $hotel->slug ?? (Str::slug($request->name) . '-' . Str::random(6)),
            'location' => $request->location,
            'category' => $request->category,
            'stars' => $request->stars ?? 0,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'facilities' => $this->normalizeFacilities($request->input('facilities')),
        ]);

        $retainIds = collect($request->input('retain_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $existingImages = $hotel->images()->get();
        $imagesToDelete = $existingImages->filter(
            fn (HotelImage $image) => ! $retainIds->contains($image->id)
        );

        foreach ($imagesToDelete as $image) {
            $this->deleteStoredImage($image->image_path);
            $image->delete();
        }

        $newFiles = $request->file('images') ?? [];
        $remainingCount = $hotel->images()->count();
        $newCount = count($newFiles);

        if ($remainingCount + $newCount > 5) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 5 gambar per hotel',
            ], 422);
        }

        $createdImageIds = [];

        if ($newCount > 0) {
            foreach ($newFiles as $index => $file) {
                $path = $file->store('hotels', 'public');
                $url = url(Storage::url($path));
                $image = $hotel->images()->create([
                    'image_path' => $url,
                    'is_primary' => false,
                ]);
                $createdImageIds[$index] = $image->id;
            }
        }

        $requestedPrimaryId = $request->filled('primary_image_id')
            ? (int) $request->input('primary_image_id')
            : null;

        $slotOrder = collect($request->input('slot_order', []))
            ->filter(fn ($entry) => is_string($entry) && $entry !== '')
            ->values();

        if ($slotOrder->isNotEmpty()) {
            foreach ($slotOrder as $entry) {
                if (str_starts_with($entry, 'existing:')) {
                    $candidateId = (int) substr($entry, 9);
                    if ($hotel->images()->where('id', $candidateId)->exists()) {
                        $requestedPrimaryId = $candidateId;
                        break;
                    }
                }

                if (str_starts_with($entry, 'new:')) {
                    $newIndex = (int) substr($entry, 4);
                    $candidateId = $createdImageIds[$newIndex] ?? null;
                    if ($candidateId && $hotel->images()->where('id', $candidateId)->exists()) {
                        $requestedPrimaryId = $candidateId;
                        break;
                    }
                }
            }
        }

        if ($requestedPrimaryId) {
            $hasRequestedPrimary = $hotel->images()->where('id', $requestedPrimaryId)->exists();
            if (! $hasRequestedPrimary) {
                return response()->json([
                    'success' => false,
                    'message' => 'Primary image tidak valid',
                ], 422);
            }
        }

        $this->syncPrimaryImage($hotel, $requestedPrimaryId);

        return response()->json(['success' => true, 'data' => $hotel->load('images')]);
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);
        if (! $hotel) {
            return response()->json(['success' => false, 'message' => 'Hotel not found'], 404);
        }
        foreach ($hotel->images as $img) {
            $this->deleteStoredImage($img->image_path);
        }
        $hotel->delete();
        return response()->json(['success' => true]);
    }
}
