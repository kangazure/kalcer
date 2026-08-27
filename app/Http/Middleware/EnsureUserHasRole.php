<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware otorisasi berbasis role untuk route Admin Dashboard.
 * Dipakai sebagai: ->middleware('role:super_admin,admin')
 *
 * Catatan: ini adalah lapisan pertahanan di level aplikasi (defense in
 * depth). Lapisan kedua tetap ada di level database melalui RLS policy
 * Supabase, sehingga meskipun middleware ini ter-bypass karena bug,
 * data tetap terlindungi di level query database.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Anda harus login untuk mengakses halaman ini.');
        }

        if (! $user->hasRoleSlug($roles)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
