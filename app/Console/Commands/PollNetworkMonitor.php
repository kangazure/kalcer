<?php

namespace App\Console\Commands;

use App\Exceptions\NetworkMonitorException;
use App\Models\NetworkMonitor;
use App\Services\Public\NetworkStatusService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PollNetworkMonitor extends Command
{
    protected $signature = 'jts:poll-network
                            {--node= : ID node spesifik, kosong = semua node aktif}
                            {--dry-run : Jalankan tanpa menyimpan ke database}';

    protected $description = 'Poll status node jaringan dan simpan histori bandwidth/latency.';

    public function __construct(protected NetworkStatusService $networkStatusService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $nodeId = $this->option('node') ? (int) $this->option('node') : null;
        $isDryRun = (bool) $this->option('dry-run');

        $nodes = NetworkMonitor::query()
            ->when($nodeId, fn ($q) => $q->where('id', $nodeId))
            ->orderBy('node_type')
            ->get();

        if ($nodes->isEmpty()) {
            $this->warn('Tidak ada node yang ditemukan.');

            return self::SUCCESS;
        }

        $this->info("Memulai polling {$nodes->count()} node...".($isDryRun ? ' [DRY-RUN]' : ''));

        $results = ['online' => 0, 'degraded' => 0, 'offline' => 0, 'errors' => 0];

        foreach ($nodes as $node) {
            try {
                $metrics = $this->pollNode($node);
                $newStatus = $this->determineStatus($metrics);

                if (! $isDryRun) {
                    $this->networkStatusService->updateNodeStatus($node->id, $newStatus, $metrics);
                }

                $results[$newStatus] = ($results[$newStatus] ?? 0) + 1;
                $this->line(" [{$newStatus}] {$node->node_name}: latency={$metrics['latency_ms']}ms loss={$metrics['packet_loss_percent']}%");
            } catch (\Throwable $e) {
                $results['errors']++;
                Log::channel('network_monitor')->error("Gagal poll node #{$node->id} ({$node->node_name}): {$e->getMessage()}");
                $this->error(" [ERROR] {$node->node_name}: {$e->getMessage()}");

                if (! $isDryRun) {
                    $this->networkStatusService->updateNodeStatus($node->id, NetworkMonitor::STATUS_UNKNOWN, []);
                }
            }
        }

        $this->info("Selesai. Online={$results['online']} Degraded={$results['degraded']} Offline={$results['offline']} Error={$results['errors']}");

        return self::SUCCESS;
    }

    protected function pollNode(NetworkMonitor $node): array
    {
        $apiUrl = config('services.network_monitor.api_url');

        if ($apiUrl && $node->ip_address) {
            return $this->pollViaApi($node, $apiUrl);
        }

        return $this->pollViaHttpPing($node);
    }

    protected function pollViaApi(NetworkMonitor $node, string $apiUrl): array
    {
        $response = Http::withHeaders(['X-Api-Key' => config('services.network_monitor.api_key')])
            ->timeout(10)
            ->get("{$apiUrl}/nodes/{$node->id}/metrics");

        if ($response->failed()) {
            throw new NetworkMonitorException("NOC API error HTTP {$response->status()} untuk node #{$node->id}", nodeId: $node->id);
        }

        $data = $response->json();

        return [
            'latency_ms' => (float) ($data['latency_ms'] ?? 999),
            'packet_loss_percent' => (float) ($data['packet_loss_percent'] ?? 100),
            'bandwidth_usage_mbps' => isset($data['bandwidth_usage_mbps']) ? (float) $data['bandwidth_usage_mbps'] : null,
            'uptime_percent' => isset($data['uptime_percent']) ? (float) $data['uptime_percent'] : null,
        ];
    }

    protected function pollViaHttpPing(NetworkMonitor $node): array
    {
        if (! $node->ip_address) {
            return ['latency_ms' => 0, 'packet_loss_percent' => 0, 'bandwidth_usage_mbps' => null];
        }

        $start = microtime(true);
        $response = Http::timeout(5)->get("http://{$node->ip_address}");
        $latencyMs = round((microtime(true) - $start) * 1000, 2);

        return [
            'latency_ms' => $response->successful() ? $latencyMs : 999,
            'packet_loss_percent' => $response->successful() ? 0 : 100,
            'bandwidth_usage_mbps' => null,
            'uptime_percent' => null,
        ];
    }

    protected function determineStatus(array $metrics): string
    {
        if ($metrics['packet_loss_percent'] >= 80) {
            return NetworkMonitor::STATUS_OFFLINE;
        }
        if ($metrics['packet_loss_percent'] >= 20 || $metrics['latency_ms'] > 200) {
            return NetworkMonitor::STATUS_DEGRADED;
        }

        return NetworkMonitor::STATUS_ONLINE;
    }
}
