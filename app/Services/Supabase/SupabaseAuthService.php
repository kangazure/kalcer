<?php

namespace App\Services\Supabase;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Menjembatani autentikasi antara Supabase Auth (auth.users) dan profil
 * aplikasi (public.users). Supabase Auth menjadi sumber kebenaran untuk
 * kredensial (password hash, email confirmation, magic link, dst),
 * sedangkan public.users menyimpan metadata aplikasi (role, status, dll).
 */
class SupabaseAuthService
{
    public function __construct(protected SupabaseClient $client)
    {
    }

    /**
     * Login menggunakan email & password melalui Supabase Auth (GoTrue).
     * Mengembalikan payload token (access_token, refresh_token, user)
     * yang kemudian dipetakan ke model App\Models\User lokal.
     */
    public function signInWithPassword(string $email, string $password): array
    {
        // Endpoint Supabase Auth (GoTrue) menerima grant_type via query
        // string dan kredensial via body JSON.
        $response = $this->client->asAnon()
            ->post('/auth/v1/token?grant_type=password', [
                'email' => $email,
                'password' => $password,
            ]);

        if ($response->failed()) {
            Log::channel('supabase')->warning('Login Supabase Auth gagal.', [
                'email' => $email,
                'status' => $response->status(),
            ]);

            throw new RuntimeException('Email atau password tidak valid.');
        }

        return $response->json();
    }

    /**
     * Mengundang user baru (admin/staff) melalui Supabase Auth Admin API.
     * Hanya bisa dipanggil dengan service_role key (server-side only).
     */
    public function inviteUserByEmail(string $email, array $appMetadata = []): array
    {
        $response = $this->client->asService()->post('/auth/v1/invite', [
            'email' => $email,
            'data' => $appMetadata,
        ]);

        if ($response->failed()) {
            Log::channel('supabase')->error('Gagal mengundang user baru via Supabase Auth.', [
                'email' => $email,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengirim undangan ke '.$email);
        }

        return $response->json();
    }

    /**
     * Sinkronisasi data auth.users -> public.users setelah login/registrasi
     * berhasil melalui Supabase Auth. Dipanggil dari AuthController.
     */
    public function syncLocalProfile(array $supabaseUser): User
    {
        return User::query()->updateOrCreate(
            ['auth_user_id' => $supabaseUser['id']],
            [
                'email' => $supabaseUser['email'],
                'email_verified_at' => ! empty($supabaseUser['email_confirmed_at']) ? now() : null,
                'name' => $supabaseUser['user_metadata']['name'] ?? $supabaseUser['email'],
            ]
        );
    }

    /**
     * Refresh access token menggunakan refresh_token yang tersimpan.
     */
    public function refreshToken(string $refreshToken): array
    {
        $response = $this->client->asAnon()
            ->post('/auth/v1/token?grant_type=refresh_token', [
                'refresh_token' => $refreshToken,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Gagal memperbarui sesi, silakan login kembali.');
        }

        return $response->json();
    }

    /**
     * Logout — mencabut refresh token di sisi Supabase Auth.
     */
    public function signOut(string $accessToken): bool
    {
        $response = $this->client->asUser($accessToken)->post('/auth/v1/logout');

        return $response->successful();
    }
}
