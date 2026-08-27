<?php

namespace App\Listeners;

use App\Events\NetworkNodeStatusChanged;
use App\Models\Notification;
use App\Models\User;
use App\Services\Integration\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyOperatorNetworkChange implements ShouldQueue
{
    public string $queue = 'jts_notifications';

    public function __construct(private TelegramService $telegramService)
    {
    }

    public function handle(NetworkNodeStatusChanged $event): void
    {
        // Hanya kirim notifikasi untuk perubahan status yang signifikan
        // (menjadi degraded/offline), bukan setiap fluktuasi kecil.
        if (! in_array($event->newStatus, ['degraded', 'offline'], true)) {
            return;
        }

        $recipients = User::query()
            ->whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'admin', 'operator']))
            ->where('status', 'active')
            ->get();

        foreach ($recipients as $recipient) {
            Notification::query()->create([
                'user_id' => $recipient->id,
                'channel' => 'database',
                'type' => 'network_status_changed',
                'title' => "Node {$event->node->node_name} {$event->newStatus}",
                'body' => "Status berubah dari {$event->previousStatus} menjadi {$event->newStatus}.",
                'action_url' => route('admin.network-monitor.show', $event->node->id),
                'metadata' => [
                    'node_id' => $event->node->id,
                    'previous_status' => $event->previousStatus,
                    'new_status' => $event->newStatus,
                ],
            ]);
        }

        try {
            $this->telegramService->sendMessage(
                "⚠️ *Perubahan Status Jaringan*\n\n"
                ."Node: {$event->node->node_name}\n"
                ."Status: {$event->previousStatus} ➜ {$event->newStatus}"
            );
        } catch (\Throwable $e) {
            Log::channel('network_monitor')->warning('Gagal mengirim notifikasi Telegram perubahan status node.', [
                'node_id' => $event->node->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
