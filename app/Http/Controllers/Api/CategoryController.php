<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
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
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
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
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'type' => $request->type,
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
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}
