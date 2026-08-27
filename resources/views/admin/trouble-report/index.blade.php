@extends('layouts.admin')
@section('page_title', 'Laporan Gangguan')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Laporan Gangguan</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Laporan Gangguan</h1><p class="text-sm text-ink-500 mt-0.5">{{ $reports->total() }} laporan</p></div>
        <div class="flex gap-2">
            @foreach(['open'=>'Open','investigating'=>'Investigating','resolved'=>'Resolved'] as $val=>$label)
                <a href="{{ route('admin.trouble-report.index', ['status'=>$val]) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request('status')===$val ? 'bg-brand text-white' : 'bg-white dark:bg-ink-900 text-ink-500 border border-ink-200 dark:border-ink-700' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Wilayah</th><th>Severity</th><th>Status</th><th>Dilaporkan</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $report->title }}</td>
                        <td class="text-sm text-ink-500">{{ $report->region_name ?? $report->node?->node_name ?? '—' }}</td>
                        <td><span class="badge text-xs {{ match($report->severity){'critical'=>'badge-red','high'=>'badge-amber',default=>'badge-blue'} }}">{{ ucfirst($report->severity) }}</span></td>
                        <td><span class="badge text-xs {{ match($report->status){'open'=>'badge-brand','investigating'=>'badge-amber','resolved'=>'badge-green',default=>'bg-ink-100 text-ink-400'} }}">{{ ucfirst($report->status) }}</span></td>
                        <td class="text-sm text-ink-400">{{ $report->reported_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.trouble-report.show', $report->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Tidak ada laporan gangguan" description="Semua sistem berjalan normal" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$reports" />
    </div>
</div>
@endsection
