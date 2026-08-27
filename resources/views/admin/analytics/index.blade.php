@extends('layouts.admin')
@section('page_title', 'Analytics')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Analytics</span>@endsection

@section('content')
<div class="space-y-6">
    <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Analytics Website</h1><p class="text-sm text-ink-500 mt-0.5">Statistik pengunjung dan performa konten</p></div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total Pengunjung (30 Hari)', 'value'=>$stats['total_visitors'] ?? 0],
            ['label'=>'Rata-rata Harian', 'value'=>$stats['avg_daily'] ?? 0],
            ['label'=>'Halaman Terpopuler', 'value'=>$stats['top_page'] ?? '-', 'isText'=>true],
            ['label'=>'Bounce Rate', 'value'=>($stats['bounce_rate'] ?? 0).'%'],
        ] as $stat)
            <div class="admin-stat-card">
                <div class="stat-value {{ !empty($stat['isText']) ? 'text-base' : '' }}">{{ !empty($stat['isText']) ? $stat['value'] : number_format((float)$stat['value']) }}</div>
                <div class="stat-label mt-1">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
        <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Trafik Pengunjung (30 Hari)</h2>
        <div class="h-64"><canvas id="traffic-chart" aria-label="Grafik trafik pengunjung"></canvas></div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Halaman Terpopuler</h2>
            <div class="space-y-2">
                @forelse($topPages ?? [] as $page)
                    <div class="flex items-center justify-between py-2 border-b border-ink-50 dark:border-ink-800 last:border-0">
                        <span class="text-sm text-ink-600 dark:text-ink-400 truncate">{{ $page->page_path }}</span>
                        <span class="text-sm font-medium text-ink-800 dark:text-ink-200">{{ number_format($page->page_views) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink-400 text-center py-6">Belum ada data</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-4">Distribusi Perangkat</h2>
            <div class="h-48"><canvas id="device-chart" aria-label="Grafik distribusi perangkat"></canvas></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const trafficData = @json($trafficChartData ?? []);
    window.initChart('traffic-chart', {
        type: 'bar',
        data: { labels: trafficData.map(d => d.date), datasets: [{ label: 'Page Views', data: trafficData.map(d => d.views), backgroundColor: '#fa8600', borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    const deviceData = @json($deviceChartData ?? ['desktop' => 0, 'mobile' => 0, 'tablet' => 0]);
    window.initChart('device-chart', {
        type: 'doughnut',
        data: {
            labels: ['Desktop', 'Mobile', 'Tablet'],
            datasets: [{ data: [deviceData.desktop, deviceData.mobile, deviceData.tablet], backgroundColor: ['#fa8600', '#feb867', '#434343'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endpush
