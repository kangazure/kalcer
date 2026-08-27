@extends('layouts.admin')
@section('page_title', $node->node_name)
@section('breadcrumb')
    <a href="{{ route('admin.network-monitor.index') }}" class="text-ink-400 hover:text-brand">Network Monitor</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span><span class="text-ink-700 dark:text-ink-300">{{ $node->node_name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="status-indicator {{ $node->status }} w-3 h-3" aria-hidden="true"></span>
            <div>
                <h1 class="text-xl font-bold text-ink-900 dark:text-white">{{ $node->node_name }}</h1>
                <p class="text-sm text-ink-500">{{ $node->ip_address }} · {{ strtoupper($node->node_type) }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.network-monitor.destroy', $node->id) }}">
            @csrf @method('DELETE')
            <button type="submit" data-confirm-delete="Hapus node '{{ $node->node_name }}'?" class="btn-outline btn-sm text-red-500 border-red-200">Hapus Node</button>
        </form>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Uptime', 'value'=> $node->uptime_percent ? number_format($node->uptime_percent,2).'%' : 'N/A'],
            ['label'=>'Latency', 'value'=> $node->latency_ms ? number_format($node->latency_ms,1).' ms' : 'N/A'],
            ['label'=>'Packet Loss', 'value'=> $node->packet_loss_percent ? number_format($node->packet_loss_percent,1).'%' : 'N/A'],
            ['label'=>'Bandwidth', 'value'=> $node->bandwidth_usage_mbps ? number_format($node->bandwidth_usage_mbps,1).' Mbps' : 'N/A'],
        ] as $stat)
            <div class="admin-stat-card"><div class="stat-value">{{ $stat['value'] }}</div><div class="stat-label mt-1">{{ $stat['label'] }}</div></div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-ink-900 dark:text-white text-sm">Bandwidth & Latency (24 Jam Terakhir)</h2>
        </div>
        <div class="h-64"><canvas id="node-chart" aria-label="Grafik bandwidth dan latency node"></canvas></div>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
        <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Edit Node</h2>
        <form method="POST" action="{{ route('admin.network-monitor.update', $node->id) }}" class="grid sm:grid-cols-2 gap-4">
            @csrf @method('PUT')
            <div><label class="form-label">Nama Node</label><input type="text" name="node_name" value="{{ $node->node_name }}" class="form-input text-sm"></div>
            <div><label class="form-label">IP Address</label><input type="text" name="ip_address" value="{{ $node->ip_address }}" class="form-input text-sm"></div>
            <div>
                <label class="form-label">Status Manual</label>
                <select name="status" class="form-select text-sm">
                    @foreach(['online'=>'Online','degraded'=>'Degraded','offline'=>'Offline','maintenance'=>'Maintenance'] as $val=>$label)
                        <option value="{{ $val }}" {{ $node->status===$val?'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="form-label">Kapasitas Bandwidth (Mbps)</label><input type="number" name="bandwidth_capacity_mbps" value="{{ $node->bandwidth_capacity_mbps }}" class="form-input text-sm"></div>
            <div class="sm:col-span-2"><button type="submit" class="btn-primary btn-sm">Simpan Perubahan</button></div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const chartData = @json($chartData ?? []);
    window.initChart('node-chart', {
        type: 'line',
        data: {
            labels: chartData.map(d => new Date(d.bucket_time).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})),
            datasets: [
                { label: 'Bandwidth (Mbps)', data: chartData.map(d => d.avg_bandwidth_mbps), borderColor: '#fa8600', backgroundColor: 'rgba(250,134,0,0.08)', fill: true, yAxisID: 'y' },
                { label: 'Latency (ms)', data: chartData.map(d => d.avg_latency_ms), borderColor: '#3b82f6', backgroundColor: 'transparent', yAxisID: 'y1' },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { type: 'linear', position: 'left' }, y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false } } }
        }
    });
});
</script>
@endpush
