<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Integrasi pengiriman pesan WhatsApp via WhatsApp Business Cloud API
 * (Meta). Dipakai untuk notifikasi internal (lead/kontak baru) dan
 * potensi balasan otomatis ke pelanggan.
 */
class WhatsAppService
{
    protected string $apiUrl;

    protected ?string $phoneNumberId;

    protected ?string $accessToken;

    public function __construct()
    {
        $this->apiUrl = rtrim((string) config('services.whatsapp.api_url'), '/');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
    }

    /**
     * Mengirim pesan teks biasa ke nomor tujuan (format internasional
     * tanpa tanda "+", contoh: 6282183999981).
     */
    public function sendTextMessage(string $to, string $message): array
    {
        $this->ensureConfigured();

        $response = Http::baseUrl($this->apiUrl)
            ->withToken($this->accessToken)
            ->timeout(10)
            ->post("/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $this->normalizeNumber($to),
                'type' => 'text',
                'text' => ['preview_url' => false, 'body' => $message],
            ]);

        if ($response->failed()) {
            Log::warning('Gagal mengirim WhatsApp message.', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengirim pesan WhatsApp.');
        }

        return $response->json();
    }

    /**
     * Verifikasi webhook callback dari Meta saat setup integrasi pertama
     * kali (challenge-response verify_token).
     */
    public function verifyWebhookChallenge(string $mode, string $token, string $challenge): ?string
    {
        if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
            return $challenge;
        }

        return null;
    }

    protected function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);

        // Normalisasi nomor lokal 08xx menjadi format internasional 628xx.
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return $number;
    }

    protected function ensureConfigured(): void
    {
        if (blank($this->phoneNumberId) || blank($this->accessToken)) {
            throw new RuntimeException('Konfigurasi WhatsApp Business API belum lengkap.');
        }
    }
}
