<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Integrasi Telegram Bot untuk notifikasi internal tim (lead baru,
 * lamaran kerja, laporan gangguan kritis, perubahan status jaringan).
 * Dipilih sebagai kanal sekunder karena lebih cepat & reliable untuk
 * notifikasi tim teknis dibanding email.
 */
class TelegramService
{
    protected ?string $botToken;

    protected ?string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage(string $text, ?string $chatId = null): array
    {
        if (blank($this->botToken)) {
            Log::info('Telegram bot token belum dikonfigurasi, notifikasi dilewati.');

            return [];
        }

        $response = Http::baseUrl(config('services.telegram.api_base').$this->botToken)
            ->timeout(10)
            ->post('/sendMessage', [
                'chat_id' => $chatId ?? $this->chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

        if ($response->failed()) {
            Log::warning('Gagal mengirim notifikasi Telegram.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Gagal mengirim notifikasi Telegram.');
        }

        return $response->json();
    }

    /**
     * Setup webhook Telegram (dipanggil sekali saat deployment/setup awal)
     * agar bot menerima update via HTTP callback, bukan polling.
     */
    public function setWebhook(string $webhookUrl): array
    {
        $response = Http::baseUrl(config('services.telegram.api_base').$this->botToken)
            ->post('/setWebhook', [
                'url' => $webhookUrl,
                'secret_token' => config('services.telegram.webhook_secret'),
            ]);

        return $response->json() ?? [];
    }
}
