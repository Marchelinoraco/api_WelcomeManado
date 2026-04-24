<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function show($id)
    {
        $category = BlogCategory::withCount('posts')->find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $category]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ko' => 'nullable|string|max:255',
            'name_zh' => 'nullable|string|max:255',
        ]);

        $category = BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'name_en' => $request->name_en,
            'name_ko' => $request->name_ko,
            'name_zh' => $request->name_zh,
        ]);

        return response()->json(['success' => true, 'data' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ko' => 'nullable|string|max:255',
            'name_zh' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'name_ko' => $request->name_ko,
            'name_zh' => $request->name_zh,
        ]);

        return response()->json(['success' => true, 'data' => $category]);
    }

    public function destroy($id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
        $category->delete();
        return response()->json(['success' => true]);
    }
}
