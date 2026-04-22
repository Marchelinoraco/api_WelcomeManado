<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAdminActivity
{
    /**
     * Mapping resource route → human-readable menu name.
     */
    private array $menuLabels = [
        'categories' => 'Kategori',
        'manado-tours' => 'Tour Lokal Manado',
        'indonesia-destinations' => 'Wisata Nasional',
        'international-tours' => 'Tour Internasional',
        'hero-images' => 'Hero Carousel',
        'hotels' => 'Hotels',
        'gallery-items' => 'Galeri',
        'transportations' => 'Transportasi',
        'transportation-bookings' => 'Booking Mobil',
        'travel-info-items' => 'Travel Info',
    ];

    /**
     * HTTP method → action mapping.
     */
    private array $methodActions = [
        'POST' => 'create',
        'PUT' => 'update',
        'PATCH' => 'update',
        'DELETE' => 'delete',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya log jika request berhasil (2xx) dan user terautentikasi
        if (
            ! $request->user() ||
            $response->getStatusCode() >= 300 ||
            ! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
        ) {
            return $response;
        }

        $this->logActivity($request, $response);

        return $response;
    }

    private function logActivity(Request $request, Response $response): void
    {
        $method = $request->method();
        $path = trim($request->path(), '/');

        // Skip auth-related routes (login/logout sudah di-log terpisah di AuthController)
        if (str_starts_with($path, 'api/admin/')) {
            return;
        }

        // Deteksi resource name dari path: api/{resource}/{id?}
        $segments = explode('/', $path);
        // Biasanya: ['api', 'resource-name', 'id']
        $resourceName = $segments[1] ?? null;

        if (! $resourceName || ! isset($this->menuLabels[$resourceName])) {
            return;
        }

        $menuLabel = $this->menuLabels[$resourceName];
        $action = $this->methodActions[$method] ?? 'unknown';

        // Bangun deskripsi berdasarkan response data
        $description = $this->buildDescription($action, $menuLabel, $response);

        AdminActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => $action,
            'menu' => $resourceName,
            'description' => $description,
            'ip_address' => $request->ip(),
        ]);
    }

    private function buildDescription(string $action, string $menuLabel, Response $response): string
    {
        $itemName = '';

        // Coba ambil nama item dari response JSON
        $content = $response->getContent();
        if ($content) {
            $data = json_decode($content, true);
            $item = $data['data'] ?? null;
            if (is_array($item)) {
                $itemName = $item['title'] ?? $item['name'] ?? $item['label'] ?? '';
            }
        }

        $actionLabels = [
            'create' => 'Menambahkan',
            'update' => 'Mengupdate',
            'delete' => 'Menghapus',
        ];

        $verb = $actionLabels[$action] ?? $action;

        return $itemName
            ? "{$verb} {$menuLabel}: {$itemName}"
            : "{$verb} {$menuLabel}";
    }
}
