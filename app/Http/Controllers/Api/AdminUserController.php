<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Return all users with selected fields only.
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'is_active', 'created_at')->get();

        return response()->json($users);
    }

    /**
     * Create a new admin user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        return response()->json(
            $user->only(['id', 'name', 'email', 'is_active', 'created_at']),
            201
        );
    }

    /**
     * Update an existing admin user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->only(['name', 'email']);

        if (filled($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json(
            $user->only(['id', 'name', 'email', 'is_active', 'created_at'])
        );
    }

    /**
     * Toggle the is_active status of a user.
     * Blocks modification of the super admin account.
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'admin@welcomemanado.com') {
            return response()->json([
                'message' => 'Akun super admin tidak dapat diubah.',
            ], 403);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        // Revoke all Sanctum tokens when deactivating
        if (! $user->is_active) {
            $user->tokens()->delete();
        }

        return response()->json(
            $user->only(['id', 'name', 'email', 'is_active', 'created_at'])
        );
    }
}
