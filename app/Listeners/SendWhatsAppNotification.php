<?php

namespace App\Listeners;

use App\Events\NewContactSubmitted;
use App\Services\Integration\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Mengirim notifikasi WhatsApp ke admin saat ada lead/pesan kontak baru,
 * agar tim sales bisa merespon dengan cepat (penting untuk konversi ISP).
 */
class SendWhatsAppNotification implements ShouldQueue
{
    public string $queue = 'jts_notifications';

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(private WhatsAppService $whatsAppService)
    {
    }

    public function handle(NewContactSubmitted $event): void
    {
        $contact = $event->contact;

        $message = "🔔 *Pesan Baru dari Website*\n\n"
            ."Nama: {$contact->name}\n"
            ."Telp: {$contact->phone}\n"
            ."Subjek: {$contact->subject}\n\n"
            ."Pesan:\n{$contact->message}";

        try {
            $this->whatsAppService->sendTextMessage(
                to: config('services.whatsapp.admin_number'),
                message: $message,
            );
        } catch (\Throwable $e) {
            // Kegagalan kirim WhatsApp tidak boleh menggagalkan flow utama
            // (pesan kontak tetap tersimpan); cukup dicatat untuk investigasi.
            Log::warning('Gagal mengirim notifikasi WhatsApp untuk kontak baru.', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
