@extends('layouts.admin')
@section('page_title', 'Testimoni')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Testimoni</span>@endsection

@section('content')
<div x-data="{ modalOpen: false, editing: null, form: { customer_name:'', customer_role:'', rating: 5, content:'', is_featured:false, is_published:true } }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Testimoni Pelanggan</h1><p class="text-sm text-ink-500 mt-0.5">{{ $testimonials->count() }} testimoni</p></div>
        <button @click="modalOpen = true; editing = null; form = { customer_name:'', customer_role:'', rating: 5, content:'', is_featured:false, is_published:true }" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Testimoni Baru
        </button>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Pelanggan</th><th>Rating</th><th>Isi</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($testimonials as $t)
                    <tr>
                        <td>
                            <div class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $t->customer_name }}</div>
                            <div class="text-xs text-ink-400">{{ $t->customer_role }}</div>
                        </td>
                        <td>{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5-$t->rating) }}</td>
                        <td class="text-sm text-ink-500 max-w-xs truncate">{{ $t->content }}</td>
                        <td>
                            <span class="badge {{ $t->is_published ? 'badge-green' : 'bg-ink-100 text-ink-400' }} text-xs">{{ $t->is_published ? 'Published' : 'Draft' }}</span>
                            @if($t->is_featured)<span class="badge-brand text-[10px] ml-1">Featured</span>@endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <button @click="modalOpen = true; editing = {{ $t->id }}; form = { customer_name: '{{ addslashes($t->customer_name) }}', customer_role: '{{ addslashes($t->customer_role ?? '') }}', rating: {{ $t->rating }}, content: '{{ addslashes($t->content) }}', is_featured: {{ $t->is_featured?'true':'false' }}, is_published: {{ $t->is_published?'true':'false' }} }" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                <form method="POST" action="{{ route('admin.testimonials.destroy', $t->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus testimoni dari '{{ $t->customer_name }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada testimoni" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-md p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4" x-text="editing ? 'Edit Testimoni' : 'Testimoni Baru'"></h2>
            <form :action="editing ? `/admin/testimonials/${editing}` : '{{ route('admin.testimonials.store') }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <div class="space-y-3">
                    <div><label class="form-label">Nama Pelanggan</label><input type="text" name="customer_name" x-model="form.customer_name" required class="form-input text-sm"></div>
                    <div><label class="form-label">Peran/Lokasi</label><input type="text" name="customer_role" x-model="form.customer_role" class="form-input text-sm"></div>
                    <div>
                        <label class="form-label">Rating</label>
                        <select name="rating" x-model="form.rating" class="form-select text-sm">
                            @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} Bintang</option>@endfor
                        </select>
                    </div>
                    <div><label class="form-label">Isi Testimoni</label><textarea name="content" x-model="form.content" rows="3" required class="form-textarea text-sm"></textarea></div>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" x-model="form.is_featured" class="rounded border-ink-300 text-brand"><span class="text-sm">Featured</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" x-model="form.is_published" class="rounded border-ink-300 text-brand"><span class="text-sm">Publikasikan</span></label>
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
