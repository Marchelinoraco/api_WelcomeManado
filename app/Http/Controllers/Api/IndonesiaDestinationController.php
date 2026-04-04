<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndonesiaDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndonesiaDestinationController extends Controller
{
    public function index()
    {
        $tours = IndonesiaDestination::with(['category', 'galleries'])->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Indonesia Destinations',
            'data'    => $tours
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'base_price'  => 'required|numeric',
            'duration_days' => 'required|integer',
            'duration_nights' => 'required|integer',
        ]);

        $tour = IndonesiaDestination::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'description' => $request->description,
            'base_price'  => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'airline_info' => $request->airline_info,
            'highlights'  => $request->highlights,
            'inclusions'  => $request->inclusions,
            'exclusions'  => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Destination created successfully',
            'data'    => $tour
        ], 201);
    }

    public function show($id)
    {
        $tour = IndonesiaDestination::with(['category', 'prices', 'itineraries', 'galleries'])->find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $tour]);
    }

    public function update(Request $request, $id)
    {
        $tour = IndonesiaDestination::find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
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
            'airline_info' => $request->airline_info,
            'highlights'  => $request->highlights,
            'inclusions'  => $request->inclusions,
            'exclusions'  => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Destination updated successfully',
            'data'    => $tour
        ]);
    }

    public function destroy($id)
    {
        $tour = IndonesiaDestination::find($id);
        if (!$tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }
        $tour->delete();
        return response()->json(['success' => true, 'message' => 'Destination deleted successfully']);
    }
}
