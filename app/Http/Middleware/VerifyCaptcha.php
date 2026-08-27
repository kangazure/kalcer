<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memvalidasi token captcha pada form publik yang rawan abuse (kontak,
 * lamaran kerja, komentar, newsletter). Mendukung dua provider:
 *
 * - Cloudflare Turnstile (primer, lebih ringan & privacy-friendly)
 * - Google reCAPTCHA v3 (fallback jika Turnstile belum dikonfigurasi)
 *
 * Provider dipilih otomatis berdasarkan token field yang dikirim:
 * `cf_turnstile_response` untuk Turnstile, `g_recaptcha_response` untuk
 * reCAPTCHA.
 */
class VerifyCaptcha
{
    public function handle(Request $request, Closure $next): Response
    {
        // Lewati validasi captcha pada environment testing/local agar
        // automated test tidak perlu memalsukan token pihak ketiga.
        if (app()->environment(['testing', 'local']) && ! config('security.captcha.force_in_local', false)) {
            return $next($request);
        }

        $turnstileToken = $request->input('cf_turnstile_response');
        $recaptchaToken = $request->input('g_recaptcha_response');

        if (blank($turnstileToken) && blank($recaptchaToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi captcha diperlukan.',
            ], 422);
        }

        $verified = $turnstileToken
            ? $this->verifyTurnstile($turnstileToken, $request->ip())
            : $this->verifyRecaptcha($recaptchaToken, $request->ip());

        if (! $verified) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi captcha gagal, silakan coba lagi.',
            ], 422);
        }

        return $next($request);
    }

    protected function verifyTurnstile(string $token, ?string $ip): bool
    {
        $response = Http::asForm()->post(config('services.turnstile.verify_url'), [
            'secret' => config('services.turnstile.secret_key'),
            'response' => $token,
            'remoteip' => $ip,
        ]);

        if ($response->failed()) {
            Log::warning('Verifikasi Cloudflare Turnstile gagal dihubungi.', ['status' => $response->status()]);

            return false;
        }

        return (bool) $response->json('success', false);
    }

    protected function verifyRecaptcha(string $token, ?string $ip): bool
    {
        $response = Http::asForm()->post(config('services.recaptcha.verify_url'), [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => $ip,
        ]);

        if ($response->failed()) {
            Log::warning('Verifikasi Google reCAPTCHA gagal dihubungi.', ['status' => $response->status()]);

            return false;
        }

        $body = $response->json();

        if (! ($body['success'] ?? false)) {
            return false;
        }

        // Untuk reCAPTCHA v3, skor < threshold dianggap kemungkinan bot.
        if (config('services.recaptcha.version') === 'v3') {
            return ($body['score'] ?? 0) >= config('services.recaptcha.score_threshold', 0.5);
        }

        return true;
    }
}
