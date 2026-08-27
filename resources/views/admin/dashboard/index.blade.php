@extends('layouts.admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-ink-900 dark:text-white">Selamat datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-sm text-ink-500 mt-0.5">{{ now()->isoFormat('dddd, D MMMM YYYY') }} — Berikut ringkasan hari ini</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Artikel Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Total Artikel', 'value' => $stats['total_posts'] ?? 0, 'change' => 'total artikel', 'icon' => 'document', 'route' => 'admin.posts.index'],
            ['label' => 'Pesan Kontak', 'value' => $stats['new_contacts'] ?? 0, 'change' => 'belum diproses', 'icon' => 'phone', 'route' => 'admin.contact.index'],
            ['label' => 'Lamaran Kerja', 'value' => $stats['pending_applications'] ?? 0, 'change' => 'menunggu review', 'icon' => 'briefcase', 'route' => 'admin.career.index'],
            ['label' => 'Laporan Gangguan', 'value' => $stats['open_trouble_reports'] ?? 0, 'change' => 'open/investigating', 'icon' => 'warning', 'route' => 'admin.trouble-report.index'],
        ] as $stat)
            <a href="{{ route($stat['route']) }}" class="admin-stat-card hover:border-brand/30 transition-all hover:-translate-y-0.5 group">
                <div class="flex items-start justify-between mb-3">
                    <div class="stat-icon">
                        @if($stat['icon'] === 'document')
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @elseif($stat['icon'] === 'phone')
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        @elseif($stat['icon'] === 'briefcase')
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        @endif
                    </div>
                    <svg class="w-4 h-4 text-ink-300 dark:text-ink-600 group-hover:text-brand group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </div>
                <div class="stat-value">{{ number_format($stat['value']) }}</div>
                <div class="stat-label mt-0.5">{{ $stat['label'] }}</div>
                <div class="text-[11px] text-ink-400 mt-1">{{ $stat['change'] }}</div>
            </a>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-ink-900 dark:text-white text-sm">Pengunjung Website (30 Hari Terakhir)</h2>
                    <p class="text-xs text-ink-400 mt-0.5">Unique visitor per hari</p>
                </div>
                <span class="badge-brand text-xs">30 hari</span>
            </div>
            <div class="h-52"><canvas id="visitors-chart" aria-label="Grafik pengunjung 30 hari terakhir"></canvas></div>
        </div>

        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-ink-900 dark:text-white text-sm">Status Jaringan</h2>
                <span class="flex items-center gap-1.5 text-xs text-green-500 font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse" aria-hidden="true"></span>
                    Live
                </span>
            </div>
            <div class="space-y-2" aria-label="Status node jaringan">
                @forelse($networkNodes ?? [] as $node)
                    <div class="flex items-center justify-between py-2 px-3 bg-ink-50 dark:bg-ink-800 rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <span class="status-indicator {{ $node->status }}" aria-hidden="true"></span>
                            <div>
                                <div class="text-xs font-medium text-ink-700 dark:text-ink-300">{{ Str::limit($node->node_name, 25) }}</div>
                                <div class="text-[10px] text-ink-400">{{ $node->uptime_percent ? number_format($node->uptime_percent, 1).'% uptime' : 'N/A' }}</div>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold capitalize {{ $node->status === 'online' ? 'text-green-500' : ($node->status === 'degraded' ? 'text-amber-500' : 'text-red-500') }}">{{ $node->status }}</span>
                    </div>
                @empty
                    <div class="text-center py-6 text-ink-400 text-sm">Belum ada node terdaftar</div>
                @endforelse
            </div>
            <a href="{{ route('admin.network-monitor.index') }}" class="block text-center text-xs text-brand hover:underline mt-3">Lihat semua node →</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-ink-900 dark:text-white text-sm">Artikel Terbaru</h2>
                <a href="{{ route('admin.posts.index') }}" class="text-xs text-brand hover:underline">Semua artikel</a>
            </div>
            <div class="space-y-3">
                @forelse($recentPosts ?? [] as $post)
                    <div class="flex items-start gap-3 py-2.5 border-b border-ink-50 dark:border-ink-800 last:border-0">
                        <div class="w-8 h-8 rounded-lg bg-brand/10 flex items-center justify-center shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-sm font-medium text-ink-800 dark:text-ink-200 hover:text-brand line-clamp-1">{{ $post->title }}</a>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="badge text-[10px] px-1.5 py-0 {{ $post->status === 'published' ? 'badge-green' : 'badge-amber' }}">{{ $post->status === 'published' ? 'Published' : ucfirst($post->status) }}</span>
                                <span class="text-[10px] text-ink-400">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-ink-400 text-sm">Belum ada artikel</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-ink-900 dark:text-white text-sm">Pesan Kontak Terbaru</h2>
                <a href="{{ route('admin.contact.index') }}" class="text-xs text-brand hover:underline">Semua pesan</a>
            </div>
            <div class="space-y-3">
                @forelse($recentContacts ?? [] as $contact)
                    <div class="flex items-start gap-3 py-2.5 border-b border-ink-50 dark:border-ink-800 last:border-0">
                        <div class="w-8 h-8 rounded-full bg-ink-100 dark:bg-ink-800 flex items-center justify-center shrink-0 text-sm font-bold text-ink-500 dark:text-ink-400" aria-hidden="true">{{ substr($contact->name, 0, 1) }}</div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('admin.contact.show', $contact->id) }}" class="text-sm font-medium text-ink-800 dark:text-ink-200 hover:text-brand">{{ $contact->name }}</a>
                            <p class="text-xs text-ink-500 line-clamp-1">{{ $contact->subject ?? $contact->message }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="badge text-[10px] px-1.5 py-0 {{ $contact->status === 'new' ? 'badge-brand' : 'badge-green' }}">{{ $contact->status === 'new' ? 'Baru' : 'Selesai' }}</span>
                                <span class="text-[10px] text-ink-400">{{ $contact->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-ink-400 text-sm">Belum ada pesan masuk</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const visitorData = @json($visitorChartData ?? []);
    if (document.getElementById('visitors-chart')) {
        window.initChart('visitors-chart', {
            type: 'line',
            data: {
                labels: visitorData.map(d => d.date),
                datasets: [{ label: 'Unique Visitor', data: visitorData.map(d => d.visitors), borderColor: '#fa8600', backgroundColor: 'rgba(250,134,0,0.08)', fill: true }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false }, ticks: { maxTicksLimit: 7, font: { size: 11 } } }, y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { font: { size: 11 } } } }
            }
        });
    }
});
</script>
@endpush
