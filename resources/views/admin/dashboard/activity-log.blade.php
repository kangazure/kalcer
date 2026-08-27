@extends('layouts.admin')
@section('page_title', 'Log Aktivitas')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Log Aktivitas</span>@endsection

@section('content')
<div class="space-y-5">
    <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Log Aktivitas Sistem</h1><p class="text-sm text-ink-500 mt-0.5">{{ $logs->total() }} entri</p></div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Pengguna</th><th>Aksi</th><th>Deskripsi</th><th>IP</th><th>Waktu</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="text-sm font-medium text-ink-700 dark:text-ink-300">{{ $log->user?->name ?? 'Sistem' }}</td>
                        <td><span class="badge-blue text-xs uppercase">{{ $log->event }}</span></td>
                        <td class="text-sm text-ink-500">{{ $log->description }}</td>
                        <td class="text-xs text-ink-400 font-mono">{{ $log->ip_address }}</td>
                        <td class="text-xs text-ink-400 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada aktivitas tercatat" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$logs" />
    </div>
</div>
@endsection
