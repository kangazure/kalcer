@extends('layouts.admin')
@section('page_title', 'Popup Promosi')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Popup</span>@endsection

@section('content')
<div x-data="{ modalOpen:false }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Popup Promosi</h1><p class="text-sm text-ink-500 mt-0.5">{{ $popups->count() }} popup</p></div>
        <button @click="modalOpen=true" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Popup Baru
        </button>
    </div>
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Aturan Tampil</th><th>Periode</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($popups as $popup)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $popup->title }}</td>
                        <td class="text-sm text-ink-500">{{ str_replace('_',' ',$popup->display_rule) }}</td>
                        <td class="text-xs text-ink-400">
                            @if($popup->starts_at) {{ $popup->starts_at->format('d M') }} — @endif
                            {{ $popup->ends_at?->format('d M Y') ?? 'Tanpa batas' }}
                        </td>
                        <td><span class="badge {{ $popup->is_active?'badge-green':'bg-ink-100 text-ink-400' }}">{{ $popup->is_active?'Aktif':'Nonaktif' }}</span></td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('admin.popups.destroy', $popup->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" data-confirm-delete="Hapus popup '{{ $popup->title }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada popup" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen=false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen=false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Popup Baru</h2>
            <form action="{{ route('admin.popups.store') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div><label class="form-label">Judul</label><input type="text" name="title" required class="form-input text-sm"></div>
                    <div><label class="form-label">Konten</label><textarea name="content" rows="3" class="form-textarea text-sm"></textarea></div>
                    <div><label class="form-label">Image URL</label><input type="url" name="image_url" class="form-input text-sm"></div>
                    <div><label class="form-label">Link URL</label><input type="url" name="link_url" class="form-input text-sm"></div>
                    <div>
                        <label class="form-label">Aturan Tampil</label>
                        <select name="display_rule" class="form-select text-sm">
                            <option value="once_per_session">Sekali per sesi</option>
                            <option value="every_visit">Setiap kunjungan</option>
                            <option value="once_per_day">Sekali per hari</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" class="rounded border-ink-300 text-brand"><span class="text-sm">Aktifkan popup ini</span></label>
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
