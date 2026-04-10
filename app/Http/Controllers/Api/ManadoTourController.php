<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\ManadoTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManadoTourController extends Controller
{
    public function index()
    {
        $tours = ManadoTour::with(['category', 'galleries'])->latest()->get();

        $tours->each(function ($t) {
            $cover = $t->galleries?->firstWhere('is_primary', true) ?? $t->galleries?->first();
            $t->cover_image = $cover?->image_path;
        });

        return response()->json([
            'success' => true,
            'message' => 'List Manado Tours',
            'data' => $tours,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric',
            'tour_type' => 'required|in:daily,package',
            'duration_hours' => 'nullable|integer|min:1',
            'duration_hours_min' => 'nullable|integer|min:1',
            'duration_hours_max' => 'nullable|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

        $tourType = $request->tour_type;
        $durationHours = $request->duration_hours ? (int) $request->duration_hours : null;
        $durationHoursMin = $request->duration_hours_min ? (int) $request->duration_hours_min : null;
        $durationHoursMax = $request->duration_hours_max ? (int) $request->duration_hours_max : null;

        $durationDays = $request->duration_days !== null ? (int) $request->duration_days : null;
        $durationNights = $request->duration_nights !== null ? (int) $request->duration_nights : null;

        if ($durationHoursMin && $durationHoursMax && $durationHoursMin > $durationHoursMax) {
            return response()->json([
                'success' => false,
                'message' => 'duration_hours_min must be less than or equal to duration_hours_max.',
            ], 422);
        }

        if ($tourType === 'daily') {
            $hasMin = (bool) $durationHoursMin;
            $hasMax = (bool) $durationHoursMax;
            $hasSingle = (bool) $durationHours;

            if (! $hasSingle && ! $hasMin && ! $hasMax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Daily tour requires duration_hours or duration_hours_min/duration_hours_max.',
                ], 422);
            }

            if ($hasMin xor $hasMax) {
                $durationHours = $durationHoursMin ?: $durationHoursMax;
                $durationHoursMin = null;
                $durationHoursMax = null;
            } elseif ($hasMin && $hasMax && $durationHoursMin === $durationHoursMax) {
                $durationHours = $durationHoursMin;
                $durationHoursMin = null;
                $durationHoursMax = null;
            } elseif ($hasMin || $hasMax) {
                $durationHours = null;
            }

            $durationDays = 1;
            $durationNights = 0;
        } else {
            if ($durationDays === null || $durationNights === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package tour requires duration_days and duration_nights.',
                ], 422);
            }
            if ($durationDays < 1 || $durationNights < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid duration_days/duration_nights.',
                ], 422);
            }
            if ($durationNights >= $durationDays) {
                return response()->json([
                    'success' => false,
                    'message' => 'duration_nights must be less than duration_days.',
                ], 422);
            }
            $durationHours = null;
            $durationHoursMin = null;
            $durationHoursMax = null;
        }

        $tour = ManadoTour::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'base_price' => $request->base_price,
            'tour_type' => $tourType,
            'duration_days' => $durationDays,
            'duration_nights' => $durationNights,
            'duration_hours' => $durationHours,
            'duration_hours_min' => $durationHoursMin,
            'duration_hours_max' => $durationHoursMax,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        foreach (array_slice($files, 0, 3) as $idx => $file) {
            $path = $file->store('tours/manado', 'public');
            $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => $idx === 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tour created successfully',
            'data' => $tour,
        ], 201);
    }

    public function show($id)
    {
        $tour = ManadoTour::with(['category', 'prices', 'itineraries', 'galleries'])->find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }

        $cover = $tour->galleries?->firstWhere('is_primary', true) ?? $tour->galleries?->first();
        $tour->cover_image = $cover?->image_path;

        return response()->json(['success' => true, 'data' => $tour]);
    }

    public function update(Request $request, $id)
    {
        $tour = ManadoTour::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'tour_type' => 'required|in:daily,package',
            'duration_hours' => 'nullable|integer|min:1',
            'duration_hours_min' => 'nullable|integer|min:1',
            'duration_hours_max' => 'nullable|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

        $tourType = $request->tour_type;
        $durationHours = $request->duration_hours ? (int) $request->duration_hours : null;
        $durationHoursMin = $request->duration_hours_min ? (int) $request->duration_hours_min : null;
        $durationHoursMax = $request->duration_hours_max ? (int) $request->duration_hours_max : null;

        $durationDays = $request->duration_days !== null ? (int) $request->duration_days : null;
        $durationNights = $request->duration_nights !== null ? (int) $request->duration_nights : null;

        if ($durationHoursMin && $durationHoursMax && $durationHoursMin > $durationHoursMax) {
            return response()->json([
                'success' => false,
                'message' => 'duration_hours_min must be less than or equal to duration_hours_max.',
            ], 422);
        }

        if ($tourType === 'daily') {
            $hasMin = (bool) $durationHoursMin;
            $hasMax = (bool) $durationHoursMax;
            $hasSingle = (bool) $durationHours;

            if (! $hasSingle && ! $hasMin && ! $hasMax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Daily tour requires duration_hours or duration_hours_min/duration_hours_max.',
                ], 422);
            }

            if ($hasMin xor $hasMax) {
                $durationHours = $durationHoursMin ?: $durationHoursMax;
                $durationHoursMin = null;
                $durationHoursMax = null;
            } elseif ($hasMin && $hasMax && $durationHoursMin === $durationHoursMax) {
                $durationHours = $durationHoursMin;
                $durationHoursMin = null;
                $durationHoursMax = null;
            } elseif ($hasMin || $hasMax) {
                $durationHours = null;
            }

            $durationDays = 1;
            $durationNights = 0;
        } else {
            if ($durationDays === null || $durationNights === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package tour requires duration_days and duration_nights.',
                ], 422);
            }
            if ($durationDays < 1 || $durationNights < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid duration_days/duration_nights.',
                ], 422);
            }
            if ($durationNights >= $durationDays) {
                return response()->json([
                    'success' => false,
                    'message' => 'duration_nights must be less than duration_days.',
                ], 422);
            }
            $durationHours = null;
            $durationHoursMin = null;
            $durationHoursMax = null;
        }

        $tour->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'base_price' => $request->base_price,
            'tour_type' => $tourType,
            'duration_days' => $durationDays,
            'duration_nights' => $durationNights,
            'duration_hours' => $durationHours,
            'duration_hours_min' => $durationHoursMin,
            'duration_hours_max' => $durationHoursMax,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        if (count($files) > 0) {
            Gallery::query()
                ->where('galleryable_type', ManadoTour::class)
                ->where('galleryable_id', $tour->id)
                ->delete();

            foreach (array_slice($files, 0, 3) as $idx => $file) {
                $path = $file->store('tours/manado', 'public');
                $tour->galleries()->create([
                    'image_path' => url(Storage::url($path)),
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tour updated successfully',
            'data' => $tour,
        ]);
    }

    public function destroy($id)
    {
        $tour = ManadoTour::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }
        $tour->delete();

        return response()->json(['success' => true, 'message' => 'Tour deleted successfully']);
    }
}
