@extends('layouts.admin')
@section('page_title', 'Detail Laporan Gangguan')
@section('breadcrumb')
    <a href="{{ route('admin.trouble-report.index') }}" class="text-ink-400 hover:text-brand">Laporan Gangguan</a>
    <span class="text-ink-400 mx-2" aria-hidden="true">/</span><span class="text-ink-700 dark:text-ink-300">#{{ $report->id }}</span>
@endsection

@section('content')
<div class="max-w-2xl space-y-5">
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h1 class="font-bold text-ink-900 dark:text-white">{{ $report->title }}</h1>
                <p class="text-sm text-ink-500 mt-0.5">{{ $report->region_name ?? $report->node?->node_name }}</p>
            </div>
            <span class="badge {{ match($report->severity){'critical'=>'badge-red','high'=>'badge-amber',default=>'badge-blue'} }}">{{ ucfirst($report->severity) }}</span>
        </div>
        <div class="bg-ink-50 dark:bg-ink-800 rounded-xl p-4 text-sm text-ink-700 dark:text-ink-300 mb-4">{{ $report->description }}</div>
        <div class="grid sm:grid-cols-2 gap-3 text-sm">
            <div><span class="text-ink-400">Pelapor:</span> <span class="text-ink-700 dark:text-ink-300">{{ $report->reporter_name ?? 'Sistem otomatis' }}</span></div>
            <div><span class="text-ink-400">Kontak:</span> <span class="text-ink-700 dark:text-ink-300">{{ $report->reporter_phone ?? '—' }}</span></div>
            <div><span class="text-ink-400">Dilaporkan:</span> <span class="text-ink-700 dark:text-ink-300">{{ $report->reported_at->format('d M Y, H:i') }}</span></div>
            @if($report->resolved_at)
                <div><span class="text-ink-400">Diselesaikan:</span> <span class="text-ink-700 dark:text-ink-300">{{ $report->resolved_at->format('d M Y, H:i') }}</span></div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-6">
        <h2 class="font-semibold text-ink-900 dark:text-white text-sm mb-3">Update Status</h2>
        <form method="POST" action="{{ route('admin.trouble-report.update', $report->id) }}">
            @csrf @method('PUT')
            <div class="space-y-3">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select text-sm">
                        @foreach(['open'=>'Open','investigating'=>'Investigating','resolved'=>'Resolved','closed'=>'Closed'] as $val=>$label)
                            <option value="{{ $val }}" {{ $report->status===$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Ditugaskan ke</label>
                    <select name="assigned_to" class="form-select text-sm">
                        <option value="">Belum ditugaskan</option>
                        @foreach($operators ?? [] as $op)
                            <option value="{{ $op->id }}" {{ $report->assigned_to==$op->id?'selected':'' }}>{{ $op->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Catatan Penyelesaian</label>
                    <textarea name="resolution_notes" rows="3" class="form-textarea text-sm">{{ $report->resolution_notes }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-primary btn-sm mt-4">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
