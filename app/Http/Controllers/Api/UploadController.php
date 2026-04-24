<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:5120',
        ]);

        $path = $request->file('upload')->store('blog/content', 'public');
        $url = url(Storage::url($path));

        // CKEditor expects this response format
        return response()->json([
            'url' => $url,
        ]);
    }
}
