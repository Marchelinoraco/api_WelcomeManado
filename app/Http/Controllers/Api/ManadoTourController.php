<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\ManadoTour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ManadoTourController extends Controller
{
    private function normalizeItineraries(mixed $rawItineraries): array
    {
        if (is_string($rawItineraries)) {
            $decoded = json_decode($rawItineraries, true);
            $rawItineraries = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        if (! is_array($rawItineraries)) {
            return [];
        }

        return collect($rawItineraries)
            ->filter(fn ($item) => is_array($item))
            ->map(function (array $item) {
                $dayNumber = (int) ($item['day_number'] ?? 0);
                $title = trim((string) ($item['title'] ?? ''));
                $description = trim((string) ($item['description'] ?? ''));
                $hotelInfo = trim((string) ($item['hotel_info'] ?? ''));
                $mealsInfo = trim((string) ($item['meals_info'] ?? ''));

                if ($dayNumber < 1) {
                    return null;
                }

                if ($title === '' && $description === '' && $hotelInfo === '' && $mealsInfo === '') {
                    return null;
                }

                return [
                    'day_number' => $dayNumber,
                    'title' => $title !== '' ? $title : "Day {$dayNumber}",
                    'description' => $description !== '' ? $description : '',
                    'hotel_info' => $hotelInfo !== '' ? $hotelInfo : null,
                    'meals_info' => $mealsInfo !== '' ? $mealsInfo : null,
                ];
            })
            ->filter()
            ->sortBy('day_number')
            ->values()
            ->all();
    }

    private function syncItineraries(ManadoTour $tour, mixed $rawItineraries): void
    {
        $tour->itineraries()->delete();

        $itineraries = $this->normalizeItineraries($rawItineraries);
        if (empty($itineraries)) {
            return;
        }

        foreach ($itineraries as $itinerary) {
            $tour->itineraries()->create($itinerary);
        }
    }

    private function deleteStoredFile(?string $fileUrl): void
    {
        $parsed = parse_url((string) $fileUrl, PHP_URL_PATH);
        $publicPrefix = '/storage/';
        if (is_string($parsed) && str_starts_with($parsed, $publicPrefix)) {
            $storagePath = substr($parsed, strlen($publicPrefix));
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function syncPrimaryGallery(ManadoTour $tour, ?int $primaryGalleryId = null): void
    {
        $galleries = $tour->galleries()->orderBy('id')->get();

        if ($galleries->isEmpty()) {
            return;
        }

        $primary = $primaryGalleryId
            ? $galleries->firstWhere('id', $primaryGalleryId)
            : $galleries->firstWhere('is_primary', true);

        if (! $primary) {
            $primary = $galleries->first();
        }

        $tour->galleries()->where('id', '!=', $primary->id)->update(['is_primary' => false]);
        if (! $primary->is_primary) {
            $primary->is_primary = true;
            $primary->save();
        }
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);
        $tours = ManadoTour::with(['category', 'galleries'])->latest()->paginate($perPage);

        $tours->getCollection()->each(function ($t) {
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
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'base_price' => 'required|numeric',
            'tour_type' => 'required|in:daily,package',
            'duration_hours' => 'nullable|integer|min:1',
            'duration_hours_min' => 'nullable|integer|min:1',
            'duration_hours_max' => 'nullable|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'itineraries' => 'nullable',
            'itinerary_pdf' => 'nullable|file|mimes:pdf|max:51200',
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

        $slug = Str::slug($request->title);
        $this->ensureSlugIsAvailable($slug);

        $tour = ManadoTour::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
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
            'itinerary_pdf_path' => null,
        ]);

        if ($request->hasFile('itinerary_pdf')) {
            $path = $request->file('itinerary_pdf')->store('tours/manado/itineraries', 'public');
            $tour->itinerary_pdf_path = url(Storage::url($path));
            $tour->save();
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        foreach (array_slice($files, 0, 5) as $idx => $file) {
            $path = $file->store('tours/manado', 'public');
            $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => $idx === 0,
            ]);
        }

        $this->syncItineraries($tour, $request->input('itineraries'));

        return response()->json([
            'success' => true,
            'message' => 'Tour created successfully',
            'data' => $tour->load(['category', 'itineraries', 'galleries']),
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
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'base_price' => 'required|numeric',
            'tour_type' => 'required|in:daily,package',
            'duration_hours' => 'nullable|integer|min:1',
            'duration_hours_min' => 'nullable|integer|min:1',
            'duration_hours_max' => 'nullable|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'itineraries' => 'nullable',
            'itinerary_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
            'retain_image_ids' => 'nullable|array|max:5',
            'retain_image_ids.*' => 'integer',
            'slot_order' => 'nullable|array|max:5',
            'slot_order.*' => 'string',
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

        $slug = $tour->slug ?: Str::slug($request->title);

        if (! $tour->slug) {
            $this->ensureSlugIsAvailable($slug, $tour->id);
        }

        if ($request->hasFile('itinerary_pdf')) {
            $previous = $tour->itinerary_pdf_path;
            $path = $request->file('itinerary_pdf')->store('tours/manado/itineraries', 'public');
            $tour->itinerary_pdf_path = url(Storage::url($path));

            if ($previous) {
                $this->deleteStoredFile($previous);
            }
        }

        $tour->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
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

        if ($request->hasFile('itinerary_pdf')) {
            $tour->save();
        }

        $retainIds = collect($request->input('retain_image_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $existingGalleries = $tour->galleries()->get();
        $galleriesToDelete = $existingGalleries->filter(
            fn (Gallery $gallery) => ! $retainIds->contains($gallery->id)
        );

        foreach ($galleriesToDelete as $gallery) {
            $this->deleteStoredFile($gallery->image_path);
            $gallery->delete();
        }

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('primary_image')) {
            $files = [$request->file('primary_image')];
        }

        $remainingCount = $tour->galleries()->count();
        $newCount = count($files);
        if ($remainingCount + $newCount > 5) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 5 gambar per tour',
            ], 422);
        }

        $createdGalleryIds = [];
        foreach (array_slice($files, 0, 5) as $index => $file) {
            $path = $file->store('tours/manado', 'public');
            $gallery = $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => false,
            ]);
            $createdGalleryIds[$index] = $gallery->id;
        }

        $requestedPrimaryId = null;
        $slotOrder = collect($request->input('slot_order', []))
            ->filter(fn ($entry) => is_string($entry) && $entry !== '')
            ->values();

        foreach ($slotOrder as $entry) {
            if (str_starts_with($entry, 'existing:')) {
                $candidateId = (int) substr($entry, 9);
                if ($tour->galleries()->where('id', $candidateId)->exists()) {
                    $requestedPrimaryId = $candidateId;
                    break;
                }
            }

            if (str_starts_with($entry, 'new:')) {
                $newIndex = (int) substr($entry, 4);
                $candidateId = $createdGalleryIds[$newIndex] ?? null;
                if ($candidateId && $tour->galleries()->where('id', $candidateId)->exists()) {
                    $requestedPrimaryId = $candidateId;
                    break;
                }
            }
        }

        $this->syncPrimaryGallery($tour, $requestedPrimaryId);
        $this->syncItineraries($tour, $request->input('itineraries'));

        return response()->json([
            'success' => true,
            'message' => 'Tour updated successfully',
            'data' => $tour->load(['category', 'itineraries', 'galleries']),
        ]);
    }

    public function destroy($id)
    {
        $tour = ManadoTour::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Tour not found'], 404);
        }

        if ($tour->itinerary_pdf_path) {
            $this->deleteStoredFile($tour->itinerary_pdf_path);
        }

        foreach ($tour->galleries as $gallery) {
            $this->deleteStoredFile($gallery->image_path);
        }
        $tour->delete();

        return response()->json(['success' => true, 'message' => 'Tour deleted successfully']);
    }

    private function ensureSlugIsAvailable(string $slug, ?int $ignoreId = null): void
    {
        $query = ManadoTour::query()->where('slug', $slug);

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
