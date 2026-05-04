<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoogleReviewStat;
use Illuminate\Http\Request;

class GoogleReviewStatController extends Controller
{
    /**
     * Get the latest Google review statistics
     */
    public function index()
    {
        $stats = GoogleReviewStat::getLatest();
        
        return response()->json([
            'success' => true,
            'data' => [
                'rating' => (float) $stats->rating,
                'review_count' => (int) $stats->review_count,
                'last_updated' => $stats->last_updated?->toIso8601String(),
            ],
        ]);
    }

    /**
     * Update Google review statistics (admin only)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:0|max:5',
            'review_count' => 'required|integer|min:0',
        ]);

        $stats = GoogleReviewStat::latest()->first();
        
        if ($stats) {
            $stats->update([
                'rating' => $validated['rating'],
                'review_count' => $validated['review_count'],
                'last_updated' => now(),
            ]);
        } else {
            $stats = GoogleReviewStat::create([
                'rating' => $validated['rating'],
                'review_count' => $validated['review_count'],
                'last_updated' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Google review stats updated successfully',
            'data' => [
                'rating' => (float) $stats->rating,
                'review_count' => (int) $stats->review_count,
                'last_updated' => $stats->last_updated?->toIso8601String(),
            ],
        ]);
    }
}
