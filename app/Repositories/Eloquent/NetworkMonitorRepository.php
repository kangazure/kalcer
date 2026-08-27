<?php

namespace App\Repositories\Eloquent;

use App\Models\NetworkMonitor;
use App\Models\NetworkMonitorHistory;
use App\Repositories\Contracts\NetworkMonitorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NetworkMonitorRepository extends BaseRepository implements NetworkMonitorRepositoryInterface
{
    protected array $searchableColumns = ['node_name', 'ip_address'];

    protected array $filterableColumns = ['node_type', 'status', 'parent_node_id'];

    public function __construct(NetworkMonitor $model)
    {
        $this->model = $model;
    }

    /**
     * Ringkasan status jaringan untuk halaman publik "Status Gangguan",
     * dikonsumsi dari VIEW public.network_status_summary (sudah menyaring
     * field sensitif seperti ip_address).
     */
    public function statusSummary(): Collection
    {
        $rows = DB::connection('pgsql')->table('network_status_summary')
            ->orderBy('node_name')
            ->get();

        return collect($rows);
    }

    public function bandwidthChartData(int $nodeId, int $hours = 24): Collection
    {
        $rows = DB::connection('pgsql')->select(
            'select * from public.get_bandwidth_chart_data(?, ?)',
            [$nodeId, $hours]
        );

        return collect($rows);
    }

    public function recordHistory(int $nodeId, array $metrics): void
    {
        NetworkMonitorHistory::query()->create([
            'node_id' => $nodeId,
            'bandwidth_usage_mbps' => $metrics['bandwidth_usage_mbps'] ?? null,
            'latency_ms' => $metrics['latency_ms'] ?? null,
            'packet_loss_percent' => $metrics['packet_loss_percent'] ?? null,
            'status' => $metrics['status'] ?? NetworkMonitor::STATUS_UNKNOWN,
            'recorded_at' => now(),
        ]);
    }

    public function updateNodeStatus(int $nodeId, string $status, array $metrics = []): void
    {
        $node = $this->findOrFail($nodeId);

        $updateData = array_filter([
            'status' => $status,
            'bandwidth_usage_mbps' => $metrics['bandwidth_usage_mbps'] ?? null,
            'latency_ms' => $metrics['latency_ms'] ?? null,
            'packet_loss_percent' => $metrics['packet_loss_percent'] ?? null,
            'uptime_percent' => $metrics['uptime_percent'] ?? null,
            'last_checked_at' => now(),
        ], fn ($value) => $value !== null);

        if ($status === NetworkMonitor::STATUS_OFFLINE && $node->status !== NetworkMonitor::STATUS_OFFLINE) {
            $updateData['last_down_at'] = now();
        }

        $node->update($updateData);

        // Catat ke histori time-series setiap kali status diperbarui agar
        // grafik bandwidth/latency punya data poin yang konsisten.
        $this->recordHistory($nodeId, array_merge($metrics, ['status' => $status]));
    }
}
