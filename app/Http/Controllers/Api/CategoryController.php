<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private function deleteStoredFile(?string $fileUrl): void
    {
        if (!$fileUrl) return;
        $parsed = parse_url($fileUrl, PHP_URL_PATH);
        if (is_string($parsed) && str_starts_with($parsed, '/storage/')) {
            Storage::disk('public')->delete(substr($parsed, strlen('/storage/')));
        }
    }

    public function index(Request $request)
    {
        $type = $request->query('type');

        $categories = Category::query()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Categories',
            'data' => $categories,
        ]);
    }

    public function byType(Request $request, ?string $type = null)
    {
        $type = $type ?? $request->route('type') ?? $request->query('type');

        if (! in_array($type, ['local', 'national', 'international'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid category type',
            ], 422);
        }

        $categories = Category::query()
            ->where('type', $type)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Categories',
            'data' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'type' => 'required|in:local,national,international',
            'image' => 'nullable|image|max:5120',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
            'image_url' => $imageUrl,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$id,
            'description' => 'nullable|string',
            'type' => 'required|in:local,national,international',
            'image' => 'nullable|image|max:5120',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $imageUrl = $category->image_url;
        if ($request->hasFile('image')) {
            $this->deleteStoredFile($category->image_url);
            $path = $request->file('image')->store('categories', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
            'image_url' => $imageUrl,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (! $category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
        $this->deleteStoredFile($category->image_url);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}
