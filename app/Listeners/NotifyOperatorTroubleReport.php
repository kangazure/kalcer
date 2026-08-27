<?php

namespace App\Listeners;

use App\Events\NewTroubleReportSubmitted;
use App\Models\Notification;
use App\Models\User;
use App\Services\Integration\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

/**
 * Memberi tahu tim Operator (NOC) secara real-time saat laporan gangguan
 * baru masuk, terutama yang berseverity high/critical, via notifikasi
 * in-app dan Telegram bot.
 */
class NotifyOperatorTroubleReport implements ShouldQueue
{
    public string $queue = 'jts_notifications';

    public function __construct(private TelegramService $telegramService)
    {
    }

    public function handle(NewTroubleReportSubmitted $event): void
    {
        $report = $event->troubleReport;

        $recipients = User::query()
            ->whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'admin', 'operator']))
            ->where('status', 'active')
            ->get();

        foreach ($recipients as $recipient) {
            Notification::query()->create([
                'user_id' => $recipient->id,
                'channel' => 'database',
                'type' => 'new_trouble_report',
                'title' => 'Laporan Gangguan Baru ('.strtoupper($report->severity).')',
                'body' => $report->title,
                'action_url' => route('admin.trouble-report.show', $report->id),
                'metadata' => [
                    'trouble_report_id' => $report->id,
                    'severity' => $report->severity,
                    'region_name' => $report->region_name,
                ],
            ]);
        }

        if (in_array($report->severity, ['high', 'critical'], true)) {
            try {
                $this->telegramService->sendMessage(
                    "🚨 *Laporan Gangguan {$report->severity}*\n\n"
                    ."Wilayah: {$report->region_name}\n"
                    ."Judul: {$report->title}\n"
                    ."Pelapor: {$report->reporter_name} ({$report->reporter_phone})"
                );
            } catch (\Throwable $e) {
                Log::channel('network_monitor')->warning('Gagal mengirim notifikasi Telegram gangguan.', [
                    'trouble_report_id' => $report->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
