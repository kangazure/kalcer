@extends('layouts.admin')
@section('page_title', 'Tag Blog')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Tag</span>@endsection

@section('content')
<div x-data="{ modalOpen: false, editing: null, form: { name: '' } }" class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Tag Artikel</h1><p class="text-sm text-ink-500 mt-0.5">{{ $tags->count() }} tag</p></div>
        <button @click="modalOpen = true; editing = null; form = { name: '' }" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tag Baru
        </button>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
        <div class="flex flex-wrap gap-2">
            @forelse($tags as $tag)
                <div class="group flex items-center gap-2 pl-3 pr-2 py-1.5 bg-ink-50 dark:bg-ink-800 rounded-full">
                    <span class="text-sm text-ink-700 dark:text-ink-300">{{ $tag->name }}</span>
                    <span class="text-xs text-ink-400">({{ $tag->usage_count }})</span>
                    <button @click="modalOpen = true; editing = {{ $tag->id }}; form = { name: '{{ addslashes($tag->name) }}' }" class="text-ink-400 hover:text-brand p-0.5" title="Edit">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag->id) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" data-confirm-delete="Hapus tag '{{ $tag->name }}'?" class="text-ink-400 hover:text-red-500 p-0.5" title="Hapus">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
            @empty
                <x-admin.empty-state title="Belum ada tag" />
            @endforelse
        </div>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="modalOpen = false">
        <div class="absolute inset-0 bg-ink-950/60 backdrop-blur-sm" @click="modalOpen = false" aria-hidden="true"></div>
        <div x-show="modalOpen" x-transition class="relative bg-white dark:bg-ink-900 rounded-2xl w-full max-w-sm p-6 shadow-2xl">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4" x-text="editing ? 'Edit Tag' : 'Tag Baru'"></h2>
            <form :action="editing ? `/admin/tags/${editing}` : '{{ route('admin.tags.store') }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>
                <label class="form-label">Nama Tag</label>
                <input type="text" name="name" x-model="form.name" required class="form-input text-sm">
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="flex-1 btn-primary btn-sm justify-center">Simpan</button>
                    <button type="button" @click="modalOpen = false" class="btn-ghost btn-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
