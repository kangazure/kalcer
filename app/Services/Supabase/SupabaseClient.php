<?php

namespace App\Services\Supabase;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

/**
 * Klien HTTP dasar untuk berkomunikasi dengan layer Supabase di luar
 * koneksi database langsung — yaitu REST (PostgREST), Auth, Storage, dan
 * Edge Functions. Dipakai sebagai dependency oleh SupabaseAuthService dan
 * SupabaseStorageService.
 *
 * Catatan keamanan: `serviceRoleKey` MEMBYPASS seluruh RLS policy. Hanya
 * dipakai untuk operasi backend tepercaya (job scheduler, sinkronisasi
 * data internal), TIDAK PERNAH diteruskan ke response yang dikirim ke
 * browser/klien.
 */
class SupabaseClient
{
    public function __construct(
        protected ?string $baseUrl,
        protected ?string $anonKey,
        protected ?string $serviceRoleKey,
        protected int $timeout = 15,
    ) {
    }

    /**
     * HTTP client yang terautentikasi sebagai service_role (bypass RLS).
     * Gunakan HANYA untuk operasi terpercaya sisi server.
     */
    public function asService(): PendingRequest
    {
        return $this->baseRequest()->withHeaders([
            'Authorization' => "Bearer {$this->serviceRoleKey}",
            'apikey' => $this->serviceRoleKey,
        ]);
    }

    /**
     * HTTP client yang terautentikasi sebagai anon key (tunduk pada RLS),
     * dipakai untuk operasi yang representasinya memang publik.
     */
    public function asAnon(): PendingRequest
    {
        return $this->baseRequest()->withHeaders([
            'Authorization' => "Bearer {$this->anonKey}",
            'apikey' => $this->anonKey,
        ]);
    }

    /**
     * HTTP client atas nama user tertentu (meneruskan access token JWT
     * milik user), sehingga RLS policy berbasis auth.uid() tetap berlaku.
     */
    public function asUser(string $userAccessToken): PendingRequest
    {
        return $this->baseRequest()->withHeaders([
            'Authorization' => "Bearer {$userAccessToken}",
            'apikey' => $this->anonKey,
        ]);
    }

    protected function baseRequest(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) $this->baseUrl, '/'))
            ->timeout($this->timeout)
            ->acceptJson()
            ->retry(2, 200, throw: false);
    }

    /**
     * Memanggil Supabase Edge Function dengan payload JSON.
     */
    public function invokeEdgeFunction(string $functionName, array $payload = []): Response
    {
        $response = $this->asService()
            ->post("/functions/v1/{$functionName}", $payload);

        if ($response->failed()) {
            Log::channel('supabase')->error("Edge function '{$functionName}' gagal dipanggil.", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }
}
