@extends('layouts.admin')
@section('page_title', 'Halaman Statis')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Halaman Statis</span>@endsection

@section('content')
<div x-data="{ modalOpen: false, editing: null, form: { key:'', label:'', value:'' } }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Halaman Statis</h1><p class="text-sm text-ink-500 mt-0.5">Kelola konten tambahan (di luar Privacy Policy & Terms yang sudah baku)</p></div>
        <button @click="modalOpen = true; editing = null; form = { key:'', label:'', value:'' }" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Halaman Baru
        </button>
    </div>

    <div class="alert-info">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>Halaman <b>Kebijakan Privasi</b> dan <b>Syarat &amp; Ketentuan</b> sudah punya template khusus dan tidak dikelola di sini. Modul ini untuk halaman tambahan lain (misal: Kebijakan Cookie, FAQ Pembayaran, dsb).</div>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Label</th><th>Key</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $page->label }}</td>
                        <td class="text-ink-500 font-mono text-xs">{{ $page->key }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <button @click="modalOpen = true; editing = {{ $page->id }}; form = { key: '{{ $page->key }}', label: '{{ addslashes($page->label) }}', value: {{ Js::from(is_string($page->value) ? json_decode($page->value, true) : $page->value) }} }" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus halaman '{{ $page->label }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3"><x-admin.empty-state title="Belum ada halaman tambahan" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-lg p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4" x-text="editing ? 'Edit Halaman' : 'Halaman Baru'"></h2>
            <form :action="editing ? `/admin/pages/${editing}` : '{{ route('admin.pages.store') }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div class="space-y-3">
                    <template x-if="!editing">
                        <div><label class="form-label">Key (unik, huruf kecil, underscore)</label><input type="text" name="key" x-model="form.key" required class="form-input text-sm" placeholder="kebijakan_cookie"></div>
                    </template>
                    <div><label class="form-label">Label</label><input type="text" name="label" x-model="form.label" required class="form-input text-sm"></div>
                    <div><label class="form-label">Konten (Markdown)</label><textarea name="value" x-model="form.value" rows="8" required class="form-textarea text-sm font-mono"></textarea></div>
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
