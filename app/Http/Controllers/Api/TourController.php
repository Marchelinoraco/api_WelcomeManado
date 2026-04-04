<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManadoTour;
use App\Models\IndonesiaDestination;
use App\Models\InternationalTour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $categorySlug = $request->get('category');

        $results = [];

        // Manado Tours
        if (!$type || $type === 'local' || $categorySlug) {
            $query = ManadoTour::with(['category', 'galleries' => fn($q) => $q->where('is_primary', true)]);
            if ($categorySlug) $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            $results['local'] = $query->latest()->get();
        }

        // Indonesia Destinations
        if (!$type || $type === 'national' || $categorySlug) {
            $query = IndonesiaDestination::with(['category', 'galleries' => fn($q) => $q->where('is_primary', true)]);
            if ($categorySlug) $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            $results['national'] = $query->latest()->get();
        }

        // International Tours
        if (!$type || $type === 'international' || $categorySlug) {
            $query = InternationalTour::with(['category', 'galleries' => fn($q) => $q->where('is_primary', true)]);
            if ($categorySlug) $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
            $results['international'] = $query->latest()->get();
        }

        // If category is specified, return flat list
        if ($categorySlug) {
            $data = $results['local']->concat($results['national'])->concat($results['international']);
        } else {
            $data = $results;
        }

        return response()->json([
            'success' => true,
            'message' => 'List Tours',
            'data'    => $data
        ]);
    }

    public function show($slug)
    {
        // Search in all three tables
        $tour = ManadoTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first()
             ?? IndonesiaDestination::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first()
             ?? InternationalTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first();

        if (!$tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Tour',
            'data'    => $tour
        ]);
    }
}
