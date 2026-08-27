<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->getAuthPassword() ?? '')) {
            return $this->error('Email atau password tidak valid.', 401);
        }

        if ($user->status !== 'active') {
            return $this->error('Akun Anda tidak aktif.', 403);
        }

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return $this->error('Gagal membuat token autentikasi.', 500);
        }

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role?->slug],
        ], 'Login berhasil.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'role' => $request->user()->role?->slug,
            'avatar_url' => $request->user()->avatar_url,
        ]);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            return $this->error('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        return $this->success(['access_token' => $token, 'token_type' => 'bearer', 'expires_in' => config('jwt.ttl') * 60]);
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (JWTException $e) {
            // Token sudah invalid/expired tetap dianggap logout berhasil.
        }

        return $this->success(message: 'Logout berhasil.');
    }
}
