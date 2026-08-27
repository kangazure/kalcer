@extends('layouts.admin')
@section('page_title', 'Galeri')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Galeri</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Album Galeri</h1><p class="text-sm text-ink-500 mt-0.5">{{ $galleries->count() }} album</p></div>
        <a href="{{ route('admin.gallery.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Album Baru
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($galleries as $gallery)
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
                <div class="h-32 bg-brand/10 flex items-center justify-center">
                    @if($gallery->cover_image_url)
                        <img src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-brand/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="badge-blue text-[10px]">{{ $gallery->category }}</span>
                        <span class="badge {{ $gallery->is_published ? 'badge-green' : 'bg-ink-100 text-ink-400' }} text-[10px]">{{ $gallery->is_published ? 'Published' : 'Draft' }}</span>
                    </div>
                    <h3 class="font-semibold text-ink-900 dark:text-white text-sm line-clamp-1 mb-3">{{ $gallery->title }}</h3>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.gallery.edit', $gallery->id) }}" class="flex-1 text-center py-1.5 text-xs text-brand border border-brand/30 rounded-lg hover:bg-brand/5">Edit</a>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $gallery->id) }}" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" data-confirm-delete="Hapus album '{{ $gallery->title }}'?" class="w-full py-1.5 text-xs text-red-500 border border-red-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full"><x-admin.empty-state title="Belum ada album galeri" :action="['url' => route('admin.gallery.create'), 'label' => 'Tambah album pertama']" /></div>
        @endforelse
    </div>
</div>
@endsection
