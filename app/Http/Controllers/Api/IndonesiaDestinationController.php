<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\IndonesiaDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IndonesiaDestinationController extends Controller
{
    public function index()
    {
        $tours = IndonesiaDestination::with(['category', 'galleries'])->latest()->get();

        $tours->each(function ($t) {
            $cover = $t->galleries?->firstWhere('is_primary', true) ?? $t->galleries?->first();
            $t->cover_image = $cover?->image_path;
        });

        return response()->json([
            'success' => true,
            'message' => 'List Indonesia Destinations',
            'data' => $tours,
        ]);
    }

    public function store(Request $request)
    {
        if (is_string($request->input('interest_tags'))) {
            $decoded = json_decode($request->input('interest_tags'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['interest_tags' => $decoded]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'duration_nights' => 'required|integer',
            'interest_tags' => 'nullable|array',
            'interest_tags.*' => 'string|max:50',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:5120',
        ]);

        $tour = IndonesiaDestination::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'airline_info' => $request->airline_info,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'interest_tags' => $request->interest_tags,
        ]);

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        foreach (array_slice($files, 0, 3) as $idx => $file) {
            $path = $file->store('tours/national', 'public');
            $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => $idx === 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Destination created successfully',
            'data' => $tour,
        ], 201);
    }

    public function show($id)
    {
        $tour = IndonesiaDestination::with(['category', 'prices', 'itineraries', 'galleries'])->find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }

        $cover = $tour->galleries?->firstWhere('is_primary', true) ?? $tour->galleries?->first();
        $tour->cover_image = $cover?->image_path;

        return response()->json(['success' => true, 'data' => $tour]);
    }

    public function update(Request $request, $id)
    {
        $tour = IndonesiaDestination::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }

        if (is_string($request->input('interest_tags'))) {
            $decoded = json_decode($request->input('interest_tags'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['interest_tags' => $decoded]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'interest_tags' => 'nullable|array',
            'interest_tags.*' => 'string|max:50',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:5120',
        ]);

        $tour->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'airline_info' => $request->airline_info,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'interest_tags' => $request->interest_tags,
        ]);

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        if (count($files) > 0) {
            Gallery::query()
                ->where('galleryable_type', IndonesiaDestination::class)
                ->where('galleryable_id', $tour->id)
                ->delete();

            foreach (array_slice($files, 0, 3) as $idx => $file) {
                $path = $file->store('tours/national', 'public');
                $tour->galleries()->create([
                    'image_path' => url(Storage::url($path)),
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Destination updated successfully',
            'data' => $tour,
        ]);
    }

    public function destroy($id)
    {
        $tour = IndonesiaDestination::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }
        $tour->delete();

        return response()->json(['success' => true, 'message' => 'Destination deleted successfully']);
    }
}
