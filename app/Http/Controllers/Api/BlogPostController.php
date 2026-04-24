<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category');

        if ($request->get('published_only')) {
            $query->where('is_published', true);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $perPage = $request->integer('per_page', 12);
        $posts = $query->orderBy('published_at', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate($perPage);

        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function show($slug)
    {
        $post = ctype_digit((string) $slug)
            ? BlogPost::with('category')->find((int) $slug)
            : BlogPost::where('slug', $slug)->with('category')->first();

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $post]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:5120',
            'title_en' => 'nullable|string|max:255',
            'title_ko' => 'nullable|string|max:255',
            'title_zh' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string',
            'excerpt_ko' => 'nullable|string',
            'excerpt_zh' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ko' => 'nullable|string',
            'content_zh' => 'nullable|string',
        ]);

        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('blog', 'public');
            $featuredImage = url(Storage::url($path));
        }

        $post = BlogPost::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'category_id' => $request->category_id,
            'author' => $request->author ?? 'admin',
            'is_published' => $request->is_published ?? false,
            'published_at' => $request->published_at ?? ($request->is_published ? now() : null),
            'title_en' => $request->title_en,
            'title_ko' => $request->title_ko,
            'title_zh' => $request->title_zh,
            'excerpt_en' => $request->excerpt_en,
            'excerpt_ko' => $request->excerpt_ko,
            'excerpt_zh' => $request->excerpt_zh,
            'content_en' => $request->content_en,
            'content_ko' => $request->content_ko,
            'content_zh' => $request->content_zh,
        ]);

        return response()->json(['success' => true, 'data' => $post->load('category')], 201);
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'author' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:5120',
            'title_en' => 'nullable|string|max:255',
            'title_ko' => 'nullable|string|max:255',
            'title_zh' => 'nullable|string|max:255',
            'excerpt_en' => 'nullable|string',
            'excerpt_ko' => 'nullable|string',
            'excerpt_zh' => 'nullable|string',
            'content_en' => 'nullable|string',
            'content_ko' => 'nullable|string',
            'content_zh' => 'nullable|string',
        ]);

        $featuredImage = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                $parsed = parse_url($post->featured_image, PHP_URL_PATH);
                $prefix = '/storage/';
                if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
                    $rel = substr($parsed, strlen($prefix));
                    Storage::disk('public')->delete($rel);
                }
            }
            $path = $request->file('featured_image')->store('blog', 'public');
            $featuredImage = url(Storage::url($path));
        }

        $post->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImage,
            'category_id' => $request->category_id,
            'author' => $request->author ?? 'admin',
            'is_published' => $request->is_published ?? false,
            'published_at' => $request->published_at ?? ($request->is_published && !$post->published_at ? now() : $post->published_at),
            'title_en' => $request->title_en,
            'title_ko' => $request->title_ko,
            'title_zh' => $request->title_zh,
            'excerpt_en' => $request->excerpt_en,
            'excerpt_ko' => $request->excerpt_ko,
            'excerpt_zh' => $request->excerpt_zh,
            'content_en' => $request->content_en,
            'content_ko' => $request->content_ko,
            'content_zh' => $request->content_zh,
        ]);

        return response()->json(['success' => true, 'data' => $post->load('category')]);
    }

    public function destroy($id)
    {
        $post = BlogPost::find($id);
        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        // Delete featured image
        if ($post->featured_image) {
            $parsed = parse_url($post->featured_image, PHP_URL_PATH);
            $prefix = '/storage/';
            if (is_string($parsed) && str_starts_with($parsed, $prefix)) {
                $rel = substr($parsed, strlen($prefix));
                Storage::disk('public')->delete($rel);
            }
        }

        $post->delete();
        return response()->json(['success' => true]);
    }
}
