<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\IndonesiaDestination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class IndonesiaDestinationController extends Controller
{
    private function syncItineraries(IndonesiaDestination $tour, mixed $rawItineraries): void
    {
        $tour->itineraries()->delete();

        if (is_string($rawItineraries)) {
            $decoded = json_decode($rawItineraries, true);
            $rawItineraries = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        if (!is_array($rawItineraries) || empty($rawItineraries)) {
            return;
        }

        foreach ($rawItineraries as $item) {
            if (!is_array($item)) continue;
            $dayNumber = (int) ($item['day_number'] ?? 0);
            $title = trim((string) ($item['title'] ?? ''));
            if ($dayNumber < 1 || $title === '') continue;

            $tour->itineraries()->create([
                'day_number'  => $dayNumber,
                'title'       => $title,
                'description' => trim((string) ($item['description'] ?? '')) ?: '',
                'hotel_info'  => trim((string) ($item['hotel_info'] ?? '')) ?: null,
                'meals_info'  => trim((string) ($item['meals_info'] ?? '')) ?: null,
            ]);
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

    private function syncPrimaryGallery(IndonesiaDestination $tour, ?int $primaryGalleryId = null): void
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
        $query = IndonesiaDestination::with(['category', 'galleries'])->latest();

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $tours = $query->paginate($perPage);

        $tours->getCollection()->each(function ($t) {
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
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'base_price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'duration_nights' => 'required|integer',
            'interest_tags' => 'nullable|array',
            'interest_tags.*' => 'string|max:50',
            'itinerary_pdf' => 'nullable|file|mimes:pdf|max:51200',
            'primary_image' => 'nullable|image|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:5120',
        ]);

        $slug = Str::slug($request->title);
        $this->ensureSlugIsAvailable($slug);

        $tour = IndonesiaDestination::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'location' => $request->location,
            'slug' => $slug,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'airline_info' => $request->airline_info,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'interest_tags' => $request->interest_tags,
            'itinerary_pdf_path' => null,
            'is_featured' => $request->boolean('is_featured'),
            'featured_badge' => $request->is_featured ? $request->featured_badge : null,
        ]);

        if ($request->hasFile('itinerary_pdf')) {
            $path = $request->file('itinerary_pdf')->store('tours/national/itineraries', 'public');
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
            $path = $file->store('tours/national', 'public');
            $tour->galleries()->create([
                'image_path' => url(Storage::url($path)),
                'is_primary' => $idx === 0,
            ]);
        }

        $this->syncItineraries($tour, $request->input('itineraries'));

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
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ko' => 'nullable|string',
            'description_zh' => 'nullable|string',
            'base_price' => 'required|numeric',
            'interest_tags' => 'nullable|array',
            'interest_tags.*' => 'string|max:50',
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

        $slug = $tour->slug ?: Str::slug($request->title);

        if (! $tour->slug) {
            $this->ensureSlugIsAvailable($slug, $tour->id);
        }

        if ($request->hasFile('itinerary_pdf')) {
            $previous = $tour->itinerary_pdf_path;
            $path = $request->file('itinerary_pdf')->store('tours/national/itineraries', 'public');
            $tour->itinerary_pdf_path = url(Storage::url($path));

            $this->deleteStoredFile($previous);
        } elseif ($request->input('remove_itinerary_pdf') === '1') {
            if ($tour->itinerary_pdf_path) {
                $this->deleteStoredFile($tour->itinerary_pdf_path);
            }
            $tour->itinerary_pdf_path = null;
        }

        $tour->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'location' => $request->location,
            'slug' => $slug,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'description_ko' => $request->description_ko,
            'description_zh' => $request->description_zh,
            'base_price' => $request->base_price,
            'duration_days' => $request->duration_days,
            'duration_nights' => $request->duration_nights,
            'airline_info' => $request->airline_info,
            'highlights' => $request->highlights,
            'inclusions' => $request->inclusions,
            'exclusions' => $request->exclusions,
            'terms_conditions' => $request->terms_conditions,
            'interest_tags' => $request->interest_tags,
            'is_featured' => $request->boolean('is_featured'),
            'featured_badge' => $request->is_featured ? $request->featured_badge : null,
        ]);

        if ($request->hasFile('itinerary_pdf') || $request->input('remove_itinerary_pdf') === '1') {
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
            $path = $file->store('tours/national', 'public');
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
            'message' => 'Destination updated successfully',
            'data' => $tour,
        ]);
    }

    public function toggleFeatured($id)
    {
        $tour = IndonesiaDestination::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }
        $tour->is_featured = ! $tour->is_featured;
        $tour->save();
        return response()->json(['success' => true, 'is_featured' => $tour->is_featured]);
    }

    public function destroy($id)
    {
        $tour = IndonesiaDestination::find($id);
        if (! $tour) {
            return response()->json(['success' => false, 'message' => 'Destination not found'], 404);
        }
        if ($tour->itinerary_pdf_path) {
            $this->deleteStoredFile($tour->itinerary_pdf_path);
        }
        foreach ($tour->galleries as $gallery) {
            $this->deleteStoredFile($gallery->image_path);
        }
        $tour->delete();

        return response()->json(['success' => true, 'message' => 'Destination deleted successfully']);
    }

    private function ensureSlugIsAvailable(string $slug, ?int $ignoreId = null): void
    {
        $query = IndonesiaDestination::query()->where('slug', $slug);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'title' => ['Judul destinasi ini menghasilkan slug yang sudah dipakai. Gunakan judul lain yang lebih spesifik.'],
            ]);
        }
    }
}
