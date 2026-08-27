<?php

namespace App\Services\Public;

use App\Events\NetworkNodeStatusChanged;
use App\Models\NetworkMonitor;
use App\Models\NetworkMonitorHistory;
use App\Models\TroubleReport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Business logic untuk status jaringan publik: ringkasan status tiap POP,
 * data chart bandwidth historis, update status node (dipanggil scheduler
 * PollNetworkMonitor), dan submit laporan gangguan dari pelanggan.
 */
class NetworkStatusService
{
    public function publicStatusSummary(): Collection
    {
        return NetworkMonitor::query()
            ->orderBy('node_type')
            ->orderBy('node_name')
            ->get(['id', 'node_name', 'node_type', 'status', 'uptime_percent', 'latency_ms', 'last_checked_at']);
    }

    public function bandwidthChart(int $nodeId, int $hours = 24): Collection
    {
        return NetworkMonitorHistory::query()
            ->selectRaw("date_trunc('hour', recorded_at) as bucket_time, avg(bandwidth_usage_mbps) as avg_bandwidth_mbps, avg(latency_ms) as avg_latency_ms")
            ->where('node_id', $nodeId)
            ->where('recorded_at', '>=', now()->subHours($hours))
            ->groupBy('bucket_time')
            ->orderBy('bucket_time')
            ->get();
    }

    public function updateNodeStatus(int $nodeId, string $status, array $metrics): void
    {
        $node = NetworkMonitor::query()->find($nodeId);

        if (! $node) {
            return;
        }

        $previousStatus = $node->status;

        $updateData = array_filter([
            'status' => $status,
            'bandwidth_usage_mbps' => $metrics['bandwidth_usage_mbps'] ?? null,
            'latency_ms' => $metrics['latency_ms'] ?? null,
            'packet_loss_percent' => $metrics['packet_loss_percent'] ?? null,
            'uptime_percent' => $metrics['uptime_percent'] ?? null,
            'last_checked_at' => now(),
        ], fn ($v) => $v !== null);

        if ($status === NetworkMonitor::STATUS_OFFLINE && $previousStatus !== NetworkMonitor::STATUS_OFFLINE) {
            $updateData['last_down_at'] = now();
        }

        $node->update($updateData);

        NetworkMonitorHistory::query()->create([
            'node_id' => $nodeId,
            'bandwidth_usage_mbps' => $metrics['bandwidth_usage_mbps'] ?? null,
            'latency_ms' => $metrics['latency_ms'] ?? null,
            'packet_loss_percent' => $metrics['packet_loss_percent'] ?? null,
            'status' => $status,
            'recorded_at' => now(),
        ]);

        if ($previousStatus !== $status) {
            event(new NetworkNodeStatusChanged($node, $previousStatus, $status));
        }
    }

    public function submitTroubleReport(array $data, Request $request): TroubleReport
    {
        return TroubleReport::query()->create([
            'reporter_name' => $data['reporter_name'],
            'reporter_phone' => $data['reporter_phone'],
            'reporter_email' => $data['reporter_email'] ?? null,
            'region_name' => $data['region_name'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'severity' => $data['severity'] ?? 'medium',
            'status' => 'open',
            'reported_at' => now(),
            'ip_address' => $request->ip(),
        ]);
    }
}
