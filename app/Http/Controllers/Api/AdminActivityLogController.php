<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminActivityLogController extends Controller
{
    /**
     * List activity logs dengan paginasi dan filter.
     */
    public function index(Request $request)
    {
        // Sementara izinkan admin utama dan akun tertentu melihat log aktivitas
        if (! in_array($request->user()->email, ['admin@welcomemanado.com', 'lino@gmail.com'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke halaman ini.',
            ], 403);
        }

        $query = AdminActivityLog::with('user:id,name,email')
            ->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by menu
        if ($request->filled('menu')) {
            $query->where('menu', $request->menu);
        }

        $logs = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $logs,
        ]);
    }
}
