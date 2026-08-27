@extends('layouts.admin')
@section('page_title', 'Maintenance')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Maintenance</span>@endsection

@section('content')
<div x-data="{ modalOpen:false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Jadwal Maintenance</h1><p class="text-sm text-ink-500 mt-0.5">{{ $maintenances->count() }} jadwal</p></div>
        <button @click="modalOpen=true" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Jadwal Baru
        </button>
    </div>
    <div class="space-y-3">
        @forelse($maintenances as $m)
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-ink-900 dark:text-white text-sm">{{ $m->title }}</h3>
                            <span class="badge text-[10px] {{ match($m->status){'scheduled'=>'badge-blue','ongoing'=>'badge-amber','completed'=>'badge-green',default=>'bg-ink-100 text-ink-400'} }}">{{ ucfirst($m->status) }}</span>
                        </div>
                        <p class="text-sm text-ink-500 mb-2">{{ $m->description }}</p>
                        <p class="text-xs text-ink-400">{{ $m->scheduled_start->format('d M Y H:i') }} — {{ $m->scheduled_end->format('d M Y H:i') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.maintenance.update', $m->id) }}">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" class="form-select text-xs">
                            @foreach(['scheduled'=>'Scheduled','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled'] as $val=>$label)
                                <option value="{{ $val }}" {{ $m->status===$val?'selected':'' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        @empty
            <x-admin.empty-state title="Belum ada jadwal maintenance" />
        @endforelse
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen=false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen=false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Jadwal Maintenance Baru</h2>
            <form action="{{ route('admin.maintenance.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div><label class="form-label">Judul</label><input type="text" name="title" required class="form-input text-sm"></div>
                    <div><label class="form-label">Deskripsi</label><textarea name="description" rows="3" required class="form-textarea text-sm"></textarea></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="form-label">Mulai</label><input type="datetime-local" name="scheduled_start" required class="form-input text-sm"></div>
                        <div><label class="form-label">Selesai</label><input type="datetime-local" name="scheduled_end" required class="form-input text-sm"></div>
                    </div>
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
