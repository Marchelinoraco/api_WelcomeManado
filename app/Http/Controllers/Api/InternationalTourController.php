<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\InternationalTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InternationalTourController extends Controller
{
    public function index()
    {
        $tours = InternationalTour::with(['category', 'galleries'])->latest()->get();

        $tours->each(function ($t) {
            $cover = $t->galleries?->firstWhere('is_primary', true) ?? $t->galleries?->first();
            $t->cover_image = $cover?->image_path;
        });

        return response()->json([
            'success' => true,
            'message' => 'List International Tours',
            'data' => $tours,
        ]);
    }

    public function store(Request $request)
    {
        if (is_string($request->input('prices'))) {
            $decoded = json_decode($request->input('prices'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['prices' => $decoded]);
            }
        }
        if (is_string($request->input('itineraries'))) {
            $decoded = json_decode($request->input('itineraries'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['itineraries' => $decoded]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'duration_nights' => 'required|integer',
            'itinerary_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'prices' => 'nullable|array|max:10',
            'prices.*.type' => 'required_with:prices|in:adult_twin,child_bed,child_no_bed',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'prices.*.tax' => 'nullable|numeric|min:0',
            'prices.*.insurance' => 'nullable|numeric|min:0',
            'prices.*.visa_fee' => 'nullable|numeric|min:0',
            'prices.*.tipping' => 'nullable|numeric|min:0',
            'itineraries' => 'nullable|array|max:30',
            'itineraries.*.day_number' => 'required_with:itineraries|integer|min:1|max:60',
            'itineraries.*.title' => 'required_with:itineraries|string|max:255',
            'itineraries.*.description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        $this->ensureSlugIsAvailable($slug);

        $tour = InternationalTour::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'visa_requirements' => $request->visa_requirements,
            'passport_validity' => $request->passport_validity,
        ]);

        if ($request->hasFile('itinerary_pdf')) {
            $path = $request->file('itinerary_pdf')->store('tours/international/itineraries', 'public');
            $tour->itinerary_pdf_path = url(Storage::url($path));
            $tour->save();
        }

        $prices = $request->input('prices');
        if (is_array($prices) && count($prices) > 0) {
            $tour->prices()->createMany(array_map(function ($p) {
                return [
                    'type' => $p['type'],
                    'price' => $p['price'],
                    'tax' => $p['tax'] ?? 0,
                    'insurance' => $p['insurance'] ?? 0,
                    'visa_fee' => $p['visa_fee'] ?? 0,
                    'tipping' => $p['tipping'] ?? 0,
                ];
            }, $prices));
        }

        $itineraries = $request->input('itineraries');
        if (is_array($itineraries) && count($itineraries) > 0) {
            $tour->itineraries()->createMany(array_map(function ($it) {
                return [
                    'day_number' => $it['day_number'],
                    'title' => $it['title'],
                    'description' => $it['description'] ?? null,
                ];
            }, $itineraries));
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        foreach (array_slice($files, 0, 3) as $idx => $file) {
            $path = $file->store('tours/international', 'public');
            $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => $idx === 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'International tour created successfully',
            'data' => $tour,
        ], 201);
    }

    public function show($id)
    {
        $tour = InternationalTour::with(['category', 'prices', 'itineraries', 'galleries'])->find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'International tour not found'], 404);
        }

        $cover = $tour->galleries?->firstWhere('is_primary', true) ?? $tour->galleries?->first();
        $tour->cover_image = $cover?->image_path;

        return response()->json(['success' => true, 'data' => $tour]);
    }

    public function update(Request $request, $id)
    {
        $tour = InternationalTour::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'International tour not found'], 404);
        }

        if (is_string($request->input('prices'))) {
            $decoded = json_decode($request->input('prices'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['prices' => $decoded]);
            }
        }
        if (is_string($request->input('itineraries'))) {
            $decoded = json_decode($request->input('itineraries'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['itineraries' => $decoded]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'itinerary_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'prices' => 'nullable|array|max:10',
            'prices.*.type' => 'required_with:prices|in:adult_twin,child_bed,child_no_bed',
            'prices.*.price' => 'required_with:prices|numeric|min:0',
            'prices.*.tax' => 'nullable|numeric|min:0',
            'prices.*.insurance' => 'nullable|numeric|min:0',
            'prices.*.visa_fee' => 'nullable|numeric|min:0',
            'prices.*.tipping' => 'nullable|numeric|min:0',
            'itineraries' => 'nullable|array|max:30',
            'itineraries.*.day_number' => 'required_with:itineraries|integer|min:1|max:60',
            'itineraries.*.title' => 'required_with:itineraries|string|max:255',
            'itineraries.*.description' => 'nullable|string',
        ]);

        $slug = $tour->slug ?: Str::slug($request->title);

        if (! $tour->slug) {
            $this->ensureSlugIsAvailable($slug, $tour->id);
        }

        if ($request->hasFile('itinerary_pdf')) {
            $previous = $tour->itinerary_pdf_path;
            $path = $request->file('itinerary_pdf')->store('tours/international/itineraries', 'public');
            $tour->itinerary_pdf_path = url(Storage::url($path));

            if ($previous) {
                $parsed = parse_url($previous, PHP_URL_PATH);
                $publicPrefix = '/storage/';
                if (is_string($parsed) && str_starts_with($parsed, $publicPrefix)) {
                    $storagePath = substr($parsed, strlen($publicPrefix));
                    Storage::disk('public')->delete($storagePath);
                }
            }
        }

        $tour->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'visa_requirements' => $request->visa_requirements,
            'passport_validity' => $request->passport_validity,
        ]);

        if ($request->hasFile('itinerary_pdf')) {
            $tour->save();
        }

        if ($request->has('prices')) {
            $prices = $request->input('prices');
            $tour->prices()->delete();
            if (is_array($prices) && count($prices) > 0) {
                $tour->prices()->createMany(array_map(function ($p) {
                    return [
                        'type' => $p['type'],
                        'price' => $p['price'],
                        'tax' => $p['tax'] ?? 0,
                        'insurance' => $p['insurance'] ?? 0,
                        'visa_fee' => $p['visa_fee'] ?? 0,
                        'tipping' => $p['tipping'] ?? 0,
                    ];
                }, $prices));
            }
        }

        if ($request->has('itineraries')) {
            $itineraries = $request->input('itineraries');
            $tour->itineraries()->delete();
            if (is_array($itineraries) && count($itineraries) > 0) {
                $tour->itineraries()->createMany(array_map(function ($it) {
                    return [
                        'day_number' => $it['day_number'],
                        'title' => $it['title'],
                        'description' => $it['description'] ?? null,
                    ];
                }, $itineraries));
            }
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        if (count($files) > 0) {
            Gallery::query()
                ->where('galleryable_type', InternationalTour::class)
                ->where('galleryable_id', $tour->id)
                ->delete();

            foreach (array_slice($files, 0, 3) as $idx => $file) {
                $path = $file->store('tours/international', 'public');
                $tour->galleries()->create([
                    'image_path' => url(Storage::url($path)),
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'International tour updated successfully',
            'data' => $tour,
        ]);
    }

    public function destroy($id)
    {
        $tour = InternationalTour::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'International tour not found'], 404);
        }
        $tour->delete();

        return response()->json(['success' => true, 'message' => 'International tour deleted successfully']);
    }

    private function ensureSlugIsAvailable(string $slug, ?int $ignoreId = null): void
    {
        $query = InternationalTour::query()->where('slug', $slug);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'title' => ['Judul tour ini menghasilkan slug yang sudah dipakai. Gunakan judul lain yang lebih spesifik.'],
            ]);
        }
    }
}
