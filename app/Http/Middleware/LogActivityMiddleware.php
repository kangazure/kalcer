<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mencatat audit trail otomatis untuk request yang bersifat mengubah data
 * (POST/PUT/PATCH/DELETE) di area admin yang sudah terautentikasi.
 * Pencatatan granular per-model tetap dilakukan di masing-masing
 * Controller/Service untuk detail yang lebih spesifik (old/new values);
 * middleware ini berfungsi sebagai pencatatan umum di level request.
 */
class LogActivityMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLog($request, $response)) {
            ActivityLog::query()->create([
                'user_id' => $request->user()?->getKey(),
                'log_name' => 'request',
                'description' => sprintf('%s %s', $request->method(), $request->path()),
                'subject_type' => null,
                'subject_id' => null,
                'event' => strtolower($request->method()),
                'properties' => [
                    'status' => $response->getStatusCode(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }

    protected function shouldLog(Request $request, Response $response): bool
    {
        if (! $request->is('admin/*')) {
            return false;
        }

        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        if (! $request->user()) {
            return false;
        }

        return $response->getStatusCode() < 500;
    }
}
