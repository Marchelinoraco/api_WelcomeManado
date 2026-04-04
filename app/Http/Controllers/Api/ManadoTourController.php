<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManadoTour;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManadoTourController extends Controller
{
    public function index()
    {
        $tours = ManadoTour::with(['category', 'galleries'])->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Manado Tours',
            'data'    => $tours
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price'  => 'required|numeric',
            'duration_days' => 'required|integer',
            'duration_nights' => 'required|integer',
        ]);

        $tour = ManadoTour::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'base_price'  => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'highlights'  => $request->highlights,
            'inclusions'  => $request->inclusions,
            'exclusions'  => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tour created successfully',
            'data'    => $tour
        ], 201);
    }

    public function show($id)
    {
        $tour = ManadoTour::with(['category', 'prices', 'itineraries', 'galleries'])->find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $tour]);
    }

    public function update(Request $request, $id)
    {
        $tour = ManadoTour::find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'base_price'  => 'required|numeric',
        ]);

        $tour->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'base_price'  => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'highlights'  => $request->highlights,
            'inclusions'  => $request->inclusions,
            'exclusions'  => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tour updated successfully',
            'data'    => $tour
        ]);
    }

    public function destroy($id)
    {
        $tour = ManadoTour::find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }
        $tour->delete();
        return response()->json(['success' => true, 'message' => 'Tour deleted successfully']);
    }
}
