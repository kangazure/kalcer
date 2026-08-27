@extends('layouts.admin')
@section('page_title', 'Coverage Area')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Coverage Area</span>@endsection

@section('content')
<div x-data="{ modalOpen:false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Wilayah Jangkauan</h1><p class="text-sm text-ink-500 mt-0.5">{{ $areas->count() }} wilayah terdaftar</p></div>
        <button @click="modalOpen=true" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Wilayah Baru
        </button>
    </div>
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Wilayah</th><th>Kecamatan</th><th>Status</th><th>POP Terhubung</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($areas as $area)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $area->region_name }}</td>
                        <td class="text-sm text-ink-500">{{ $area->district }}</td>
                        <td>
                            <span class="badge text-xs {{ match($area->coverage_status){'available'=>'badge-green','partial'=>'badge-amber',default=>'badge-blue'} }}">
                                {{ match($area->coverage_status){'available'=>'Tersedia','partial'=>'Sebagian',default=>'Rencana'} }}
                            </span>
                        </td>
                        <td class="text-sm text-ink-500">{{ $area->pop?->node_name ?? '—' }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <button class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit" onclick="alert('Gunakan form edit di halaman terpisah')"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <form method="POST" action="{{ route('admin.coverage-area.destroy', $area->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus wilayah '{{ $area->region_name }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada wilayah jangkauan" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen=false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen=false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Wilayah Baru</h2>
            <form action="{{ route('admin.coverage-area.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div><label class="form-label">Nama Wilayah</label><input type="text" name="region_name" required class="form-input text-sm" placeholder="Raman Utara"></div>
                    <div><label class="form-label">Kecamatan</label><input type="text" name="district" class="form-input text-sm"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div><label class="form-label">Latitude</label><input type="number" step="0.000001" name="center_latitude" required class="form-input text-sm"></div>
                        <div><label class="form-label">Longitude</label><input type="number" step="0.000001" name="center_longitude" required class="form-input text-sm"></div>
                    </div>
                    <div><label class="form-label">Radius (meter)</label><input type="number" name="radius_meters" value="3000" class="form-input text-sm"></div>
                    <div>
                        <label class="form-label">Status Jangkauan</label>
                        <select name="coverage_status" class="form-select text-sm">
                            <option value="available">Tersedia</option>
                            <option value="partial">Sebagian</option>
                            <option value="planned">Rencana</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">POP Terhubung</label>
                        <select name="pop_id" class="form-select text-sm">
                            <option value="">Belum ditentukan</option>
                            @foreach($nodes ?? [] as $node)
                                <option value="{{ $node->id }}">{{ $node->node_name }}</option>
                            @endforeach
                        </select>
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
