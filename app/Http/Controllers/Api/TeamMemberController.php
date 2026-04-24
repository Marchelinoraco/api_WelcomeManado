<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    private function deleteStoredFile(?string $url): void
    {
        if (!$url) return;
        $parsed = parse_url($url, PHP_URL_PATH);
        if (is_string($parsed) && str_starts_with($parsed, '/storage/')) {
            Storage::disk('public')->delete(substr($parsed, strlen('/storage/')));
        }
    }

    public function index()
    {
        $members = TeamMember::where('is_active', true)
            ->orderBy('order')
            ->orderBy('created_at')
            ->get();
        return response()->json(['success' => true, 'data' => $members]);
    }

    public function adminIndex()
    {
        $members = TeamMember::orderBy('order')->orderBy('created_at')->get();
        return response()->json(['success' => true, 'data' => $members]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|max:5120',
            'name'        => 'required|string|max:255',
            'name_en'     => 'nullable|string|max:255',
            'name_ko'     => 'nullable|string|max:255',
            'name_zh'     => 'nullable|string|max:255',
            'position'    => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_ko' => 'nullable|string|max:255',
            'position_zh' => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('team', 'public');

        $member = TeamMember::create([
            'image_url'   => url(Storage::url($path)),
            'name'        => $request->name,
            'name_en'     => $request->name_en,
            'name_ko'     => $request->name_ko,
            'name_zh'     => $request->name_zh,
            'position'    => $request->position,
            'position_en' => $request->position_en,
            'position_ko' => $request->position_ko,
            'position_zh' => $request->position_zh,
            'order'       => (int) ($request->order ?? 0),
            'is_active'   => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return response()->json(['success' => true, 'data' => $member], 201);
    }

    public function update(Request $request, $id)
    {
        $member = TeamMember::find($id);
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $request->validate([
            'image'       => 'nullable|image|max:5120',
            'name'        => 'required|string|max:255',
            'name_en'     => 'nullable|string|max:255',
            'name_ko'     => 'nullable|string|max:255',
            'name_zh'     => 'nullable|string|max:255',
            'position'    => 'required|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_ko' => 'nullable|string|max:255',
            'position_zh' => 'nullable|string|max:255',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $imageUrl = $member->image_url;
        if ($request->hasFile('image')) {
            $this->deleteStoredFile($member->image_url);
            $path = $request->file('image')->store('team', 'public');
            $imageUrl = url(Storage::url($path));
        }

        $member->update([
            'image_url'   => $imageUrl,
            'name'        => $request->name,
            'name_en'     => $request->name_en,
            'name_ko'     => $request->name_ko,
            'name_zh'     => $request->name_zh,
            'position'    => $request->position,
            'position_en' => $request->position_en,
            'position_ko' => $request->position_ko,
            'position_zh' => $request->position_zh,
            'order'       => (int) ($request->order ?? $member->order),
            'is_active'   => $request->has('is_active') ? (bool) $request->is_active : $member->is_active,
        ]);

        return response()->json(['success' => true, 'data' => $member]);
    }

    public function destroy($id)
    {
        $member = TeamMember::find($id);
        if (!$member) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        $this->deleteStoredFile($member->image_url);
        $member->delete();
        return response()->json(['success' => true]);
    }
}
