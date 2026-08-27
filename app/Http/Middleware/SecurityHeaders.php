<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan security header standar ke SETIAP response (HTML maupun
 * JSON) untuk mitigasi clickjacking, MIME sniffing, dan memaksa HTTPS.
 * Konfigurasi nilai header diambil dari config/security.php agar mudah
 * disesuaikan tanpa menyentuh kode middleware.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = config('security.headers');

        $response->headers->set('X-Frame-Options', $headers['x_frame_options']);
        $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options']);
        $response->headers->set('X-XSS-Protection', $headers['x_xss_protection']);
        $response->headers->set('Referrer-Policy', $headers['referrer_policy']);
        $response->headers->set('Permissions-Policy', $headers['permissions_policy']);
        $response->headers->set('Content-Security-Policy', $headers['content_security_policy']);

        // HSTS hanya relevan saat request memang sudah melalui HTTPS,
        // mencegah header ini ter-set pada lingkungan development HTTP lokal.
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', $headers['strict_transport_security']);
        }

        // Hapus header yang membocorkan detail teknologi stack.
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
