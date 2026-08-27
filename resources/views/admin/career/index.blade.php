@extends('layouts.admin')
@section('page_title', 'Karir')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Karir</span>@endsection

@section('content')
<div x-data="{ modalOpen: false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Lowongan Kerja</h1><p class="text-sm text-ink-500 mt-0.5">{{ $careers->count() }} lowongan</p></div>
        <button @click="modalOpen = true" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Lowongan Baru
        </button>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Lokasi</th><th>Tipe</th><th>Lamaran Masuk</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($careers as $career)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $career->title }}</td>
                        <td class="text-sm text-ink-500">{{ $career->location }}</td>
                        <td><span class="badge-blue text-xs">{{ str_replace('_',' ',ucfirst($career->job_type)) }}</span></td>
                        <td>
                            <a href="{{ route('admin.career.applications', $career->id) }}" class="text-brand hover:underline text-sm font-medium">
                                {{ $career->applications_count ?? 0 }} lamaran
                            </a>
                        </td>
                        <td><span class="badge {{ $career->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }} text-xs">{{ $career->is_active ? 'Dibuka' : 'Ditutup' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('career.show', $career->slug) }}" target="_blank" rel="noopener noreferrer" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Lihat"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>
                                <form method="POST" action="{{ route('admin.career.destroy', $career->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus lowongan '{{ $career->title }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada lowongan" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-lg p-6 shadow-2xl max-h-[90vh] overflow-y-auto"
             x-data="{ requirements: [''], responsibilities: [''], benefits: [''] }">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Lowongan Baru</h2>
            <form action="{{ route('admin.career.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div><label class="form-label">Judul Posisi</label><input type="text" name="title" required class="form-input text-sm"></div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div><label class="form-label">Departemen</label><input type="text" name="department" class="form-input text-sm"></div>
                        <div><label class="form-label">Lokasi</label><input type="text" name="location" value="Lampung Timur" required class="form-input text-sm"></div>
                    </div>
                    <div>
                        <label class="form-label">Tipe Pekerjaan</label>
                        <select name="job_type" class="form-select text-sm">
                            @foreach(['full_time'=>'Full Time','part_time'=>'Part Time','internship'=>'Internship','contract'=>'Kontrak','remote'=>'Remote'] as $val=>$label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="form-label">Deskripsi Pekerjaan</label><textarea name="description" rows="4" required class="form-textarea text-sm"></textarea></div>

                    <div>
                        <div class="flex items-center justify-between mb-1"><label class="form-label mb-0">Kualifikasi</label><button type="button" @click="requirements.push('')" class="text-xs text-brand hover:underline">+ Tambah</button></div>
                        <template x-for="(r,i) in requirements" :key="i"><div class="flex gap-2 mb-2"><input type="text" :name="`requirements[${i}]`" x-model="requirements[i]" class="form-input text-sm"><button type="button" @click="requirements.splice(i,1)" class="text-red-400 px-2">✕</button></div></template>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1"><label class="form-label mb-0">Tanggung Jawab</label><button type="button" @click="responsibilities.push('')" class="text-xs text-brand hover:underline">+ Tambah</button></div>
                        <template x-for="(r,i) in responsibilities" :key="i"><div class="flex gap-2 mb-2"><input type="text" :name="`responsibilities[${i}]`" x-model="responsibilities[i]" class="form-input text-sm"><button type="button" @click="responsibilities.splice(i,1)" class="text-red-400 px-2">✕</button></div></template>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1"><label class="form-label mb-0">Benefit</label><button type="button" @click="benefits.push('')" class="text-xs text-brand hover:underline">+ Tambah</button></div>
                        <template x-for="(r,i) in benefits" :key="i"><div class="flex gap-2 mb-2"><input type="text" :name="`benefits[${i}]`" x-model="benefits[i]" class="form-input text-sm"><button type="button" @click="benefits.splice(i,1)" class="text-red-400 px-2">✕</button></div></template>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div><label class="form-label">Jumlah Posisi</label><input type="number" name="vacancy_count" value="1" min="1" class="form-input text-sm"></div>
                        <div><label class="form-label">Batas Lamaran</label><input type="date" name="closes_at" class="form-input text-sm"></div>
                    </div>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked class="rounded border-ink-300 text-brand"><span class="text-sm">Buka lowongan ini</span></label>
                </div>
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <button type="button" @click="modalOpen = false" class="btn-ghost btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
