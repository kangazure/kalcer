@extends('layouts.admin')
@section('page_title', 'Kategori Blog')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Kategori</span>@endsection

@section('content')
<div x-data="{ modalOpen: false, editing: null, form: { name: '', description: '', parent_id: '', is_active: true } }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Kategori Artikel</h1><p class="text-sm text-ink-500 mt-0.5">{{ $categories->count() }} kategori</p></div>
        <button @click="modalOpen = true; editing = null; form = { name: '', description: '', parent_id: '', is_active: true }" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Kategori Baru
        </button>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Slug</th><th>Jumlah Artikel</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200">{{ $category->name }}</td>
                        <td class="text-ink-500 font-mono text-xs">{{ $category->slug }}</td>
                        <td>{{ $category->posts_count ?? 0 }}</td>
                        <td><span class="badge {{ $category->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <button @click="modalOpen = true; editing = {{ $category->id }}; form = { name: '{{ addslashes($category->name) }}', description: '{{ addslashes($category->description ?? '') }}', parent_id: '{{ $category->parent_id }}', is_active: {{ $category->is_active ? 'true' : 'false' }} }" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Yakin hapus kategori '{{ $category->name }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada kategori" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4" x-text="editing ? 'Edit Kategori' : 'Kategori Baru'"></h2>
            <form :action="editing ? `/admin/categories/${editing}` : '{{ route('admin.categories.store') }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div class="space-y-3">
                    <div><label class="form-label">Nama Kategori</label><input type="text" name="name" x-model="form.name" required class="form-input text-sm"></div>
                    <div><label class="form-label">Deskripsi</label><textarea name="description" x-model="form.description" rows="2" class="form-textarea text-sm"></textarea></div>
                    <div>
                        <label class="form-label">Parent Kategori (opsional)</label>
                        <select name="parent_id" x-model="form.parent_id" class="form-select text-sm">
                            <option value="">Tidak ada</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-ink-300 text-brand"><span class="text-sm text-ink-700 dark:text-ink-300">Aktif</span></label>
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
