<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndonesiaDestination;
use App\Models\InternationalTour;
use App\Models\ManadoTour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $categorySlug = $request->get('category');

        $results = [];

        // Manado Tours
        if (! $type || $type === 'local' || $categorySlug) {
            $query = ManadoTour::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
            if ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            }
            $results['local'] = $query->latest()->get();
        }

        // Indonesia Destinations
        if (! $type || $type === 'national' || $categorySlug) {
            $query = IndonesiaDestination::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
            if ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            }
            $results['national'] = $query->latest()->get();
        }

        // International Tours
        if (! $type || $type === 'international' || $categorySlug) {
            $query = InternationalTour::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
            if ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            }
            $results['international'] = $query->latest()->get();
        }

        // If category is specified, return flat list
        if ($categorySlug) {
            $data = $results['local']->concat($results['national'])->concat($results['international']);
        } elseif ($type && in_array($type, ['local', 'national', 'international'], true)) {
            $data = $results[$type] ?? [];
        } else {
            $data = $results;
        }

        return response()->json([
            'success' => true,
            'message' => 'List Tours',
            'data' => $data,
        ]);
    }

    public function show($slug)
    {
        // Search in all three tables
        $tour = ManadoTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first()
             ?? IndonesiaDestination::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first()
             ?? InternationalTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first();

        if (! $tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Tour',
            'data' => $tour,
        ]);
    }

    public function localIndex(Request $request)
    {
        $categorySlug = $request->query('category');

        $query = ManadoTour::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        $tours = $query->latest()->get()->map(fn ($t) => $this->withCoverImage($t));

        return response()->json([
            'success' => true,
            'message' => 'List Local Tours',
            'data' => $tours,
        ]);
    }

    public function localShow(string $slug)
    {
        $tour = ManadoTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first();

        if (! $tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found',
            ], 404);
        }

        $tour = $this->withPriceDetails($tour);

        return response()->json([
            'success' => true,
            'message' => 'Detail Local Tour',
            'data' => $tour,
        ]);
    }

    public function nationalIndex(Request $request)
    {
        $categorySlug = $request->query('category');

        $query = IndonesiaDestination::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        $tours = $query->latest()->get()->map(fn ($t) => $this->withCoverImage($t));

        return response()->json([
            'success' => true,
            'message' => 'List National Tours',
            'data' => $tours,
        ]);
    }

    public function nationalShow(string $slug)
    {
        $tour = IndonesiaDestination::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first();

        if (! $tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found',
            ], 404);
        }

        $tour = $this->withPriceDetails($tour);

        return response()->json([
            'success' => true,
            'message' => 'Detail National Tour',
            'data' => $tour,
        ]);
    }

    public function internationalIndex(Request $request)
    {
        $categorySlug = $request->query('category');

        $query = InternationalTour::with(['category', 'galleries' => fn ($q) => $q->where('is_primary', true)]);
        if ($categorySlug) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        $tours = $query->latest()->get()->map(fn ($t) => $this->withCoverImage($t));

        return response()->json([
            'success' => true,
            'message' => 'List International Tours',
            'data' => $tours,
        ]);
    }

    public function internationalShow(string $slug)
    {
        $tour = InternationalTour::where('slug', $slug)->with(['category', 'prices', 'itineraries', 'galleries'])->first();

        if (! $tour) {
            return response()->json([
                'success' => false,
                'message' => 'Tour not found',
            ], 404);
        }

        $tour = $this->withPriceDetails($tour);

        return response()->json([
            'success' => true,
            'message' => 'Detail International Tour',
            'data' => $tour,
        ]);
    }

    private function withCoverImage($tour)
    {
        $cover = $tour->galleries?->first();
        $tour->cover_image = $cover?->image_path;

        return $tour;
    }

    private function withPriceDetails($tour)
    {
        $prices = $tour->prices ?? collect();

        $tour->price_details = $prices->map(function ($p) {
            $base = (float) $p->price;
            $tax = (float) $p->tax;
            $insurance = (float) $p->insurance;
            $visaFee = (float) $p->visa_fee;
            $tipping = (float) $p->tipping;

            $label = match ($p->type) {
                'adult_twin' => 'Dewasa (Twin Share)',
                'child_bed' => 'Anak (Dengan Bed)',
                'child_no_bed' => 'Anak (Tanpa Bed)',
                default => $p->type,
            };

            return [
                'type' => $p->type,
                'label' => $label,
                'base_price' => $base,
                'tax' => $tax,
                'insurance' => $insurance,
                'visa_fee' => $visaFee,
                'tipping' => $tipping,
                'total' => $base + $tax + $insurance + $visaFee + $tipping,
            ];
        })->values();

        return $tour;
    }
}
