@extends('layouts.admin')
@section('page_title', 'Network Monitor')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Network Monitor</span>@endsection

@section('content')
<div x-data="{ modalOpen:false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Network Monitor</h1><p class="text-sm text-ink-500 mt-0.5">{{ $nodes->count() }} node terpantau</p></div>
        <button @click="modalOpen=true" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Node Baru
        </button>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($nodes as $node)
            <a href="{{ route('admin.network-monitor.show', $node->id) }}" class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5 hover:border-brand/30 transition-all block">
                <div class="flex items-start justify-between mb-3">
                    <span class="badge-blue text-[10px]">{{ strtoupper($node->node_type) }}</span>
                    <span class="status-indicator {{ $node->status }}" aria-label="Status: {{ $node->status }}"></span>
                </div>
                <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-1">{{ $node->node_name }}</h3>
                <p class="text-xs text-ink-400 mb-3">{{ $node->ip_address ?? 'IP tidak tercatat' }}</p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div><span class="text-ink-400">Uptime</span><div class="font-semibold text-ink-700 dark:text-ink-300">{{ $node->uptime_percent ? number_format($node->uptime_percent,1).'%' : 'N/A' }}</div></div>
                    <div><span class="text-ink-400">Latency</span><div class="font-semibold text-ink-700 dark:text-ink-300">{{ $node->latency_ms ? number_format($node->latency_ms,1).'ms' : 'N/A' }}</div></div>
                </div>
            </a>
        @empty
            <div class="col-span-full"><x-admin.empty-state title="Belum ada node terdaftar" /></div>
        @endforelse
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen=false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen=false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Node Baru</h2>
            <form action="{{ route('admin.network-monitor.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div><label class="form-label">Nama Node</label><input type="text" name="node_name" required class="form-input text-sm" placeholder="POP Raman Utara"></div>
                    <div>
                        <label class="form-label">Tipe Node</label>
                        <select name="node_type" class="form-select text-sm">
                            @foreach(['pop'=>'POP','backbone'=>'Backbone','core'=>'Core','access_point'=>'Access Point'] as $val=>$label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="form-label">IP Address</label><input type="text" name="ip_address" class="form-input text-sm" placeholder="10.0.1.1"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="form-label">Latitude</label><input type="number" step="0.000001" name="latitude" class="form-input text-sm"></div>
                        <div><label class="form-label">Longitude</label><input type="number" step="0.000001" name="longitude" class="form-input text-sm"></div>
                    </div>
                    <div><label class="form-label">Kapasitas Bandwidth (Mbps)</label><input type="number" name="bandwidth_capacity_mbps" class="form-input text-sm"></div>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <button type="button" @click="modalOpen=false" class="btn-ghost btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
