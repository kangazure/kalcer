@extends('layouts.admin')
@section('page_title', 'Visitor Log')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Visitor</span>@endsection

@section('content')
<div class="space-y-5">
    <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Log Pengunjung</h1><p class="text-sm text-ink-500 mt-0.5">{{ $visitors->total() }} kunjungan tercatat</p></div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Halaman</th><th>Perangkat</th><th>Referrer</th><th>IP</th><th>Waktu</th></tr></thead>
            <tbody>
                @forelse($visitors as $visitor)
                    <tr>
                        <td class="text-sm text-ink-700 dark:text-ink-300 max-w-xs truncate">{{ $visitor->landing_page }}</td>
                        <td><span class="badge-blue text-xs capitalize">{{ $visitor->device_type ?? 'unknown' }}</span></td>
                        <td class="text-sm text-ink-400 max-w-xs truncate">{{ $visitor->referrer ?? 'Direct' }}</td>
                        <td class="text-xs text-ink-400 font-mono">{{ $visitor->ip_address }}</td>
                        <td class="text-xs text-ink-400 whitespace-nowrap">{{ $visitor->visited_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada data pengunjung" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$visitors" />
    </div>
</div>
@endsection
