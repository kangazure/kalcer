<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mencatat kunjungan halaman publik untuk analytics internal ringan
 * (selain Google Analytics). Memakai cache untuk deduplikasi sederhana
 * per session_id+path dalam window singkat, supaya refresh berulang
 * tidak menggandakan hitungan page view secara berlebihan.
 */
class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            $this->record($request);
        }

        return $response;
    }

    protected function shouldTrack(Request $request, Response $response): bool
    {
        if ($request->method() !== 'GET') {
            return false;
        }

        if ($request->is('admin/*') || $request->is('api/*') || $request->is('_debugbar/*')) {
            return false;
        }

        // Hindari mencatat asset statis yang ter-handle oleh route fallback.
        if (preg_match('/\.(css|js|png|jpg|jpeg|svg|webp|ico|woff2?|map)$/i', $request->path())) {
            return false;
        }

        return $response->getStatusCode() < 400;
    }

    protected function record(Request $request): void
    {
        $sessionId = $request->session()->getId();
        $cacheKey = "visitor_tracked:{$sessionId}:{$request->path()}";

        // Dedup window 5 menit — mencegah satu sesi membanjiri tabel visitor
        // hanya karena reload halaman berulang.
        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addMinutes(5));

        Visitor::query()->create([
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'landing_page' => $request->fullUrl(),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'visited_at' => now(),
        ]);
    }

    protected function detectDeviceType(?string $userAgent): string
    {
        if (blank($userAgent)) {
            return 'unknown';
        }

        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'tablet';
        }

        if (preg_match('/mobile|android|iphone/i', $userAgent)) {
            return 'mobile';
        }

        return 'desktop';
    }
}
