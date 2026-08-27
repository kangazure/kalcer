@extends('layouts.admin')
@section('page_title', 'Manajemen Artikel')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Artikel</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-ink-900 dark:text-white">Artikel Blog</h1>
            <p class="text-sm text-ink-500 mt-0.5">{{ $posts->total() }} artikel ditemukan</p>
        </div>
        @can('create', \App\Models\Post::class)
            <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tulis Artikel
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('admin.posts.index') }}" class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau konten artikel..." class="form-input pl-10 text-sm">
                </div>
            </div>
            <select name="status" onchange="this.form.submit()" class="form-select text-sm min-w-[140px]">
                <option value="">Semua Status</option>
                @foreach(['draft' => 'Draft', 'review' => 'Review', 'published' => 'Published', 'archived' => 'Archived'] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="category_id" onchange="this.form.submit()" class="form-select text-sm min-w-[160px]">
                <option value="">Semua Kategori</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline btn-sm">Cari</button>
        </div>
    </form>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead><tr><th>Artikel</th><th>Status</th><th>Kategori</th><th>View</th><th>Tanggal</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <div class="flex items-start gap-3">
                                    @if($post->cover_image_url)
                                        <img src="{{ $post->cover_image_url }}" alt="" class="w-12 h-9 object-cover rounded-lg shrink-0" loading="lazy">
                                    @else
                                        <div class="w-12 h-9 rounded-lg bg-brand/10 flex items-center justify-center shrink-0" aria-hidden="true">
                                            <svg class="w-4 h-4 text-brand/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="font-medium text-ink-800 dark:text-ink-200 hover:text-brand text-sm line-clamp-1">{{ $post->title }}</a>
                                        <p class="text-xs text-ink-400 mt-0.5">{{ $post->author?->name ?? 'N/A' }} · {{ $post->reading_time_minutes }} mnt</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge text-xs {{ match($post->status) {'published' => 'badge-green','draft' => 'bg-ink-100 dark:bg-ink-800 text-ink-500','review' => 'badge-amber','archived' => 'bg-ink-100 dark:bg-ink-800 text-ink-400',default => 'badge-blue'} }}">{{ ucfirst($post->status) }}</span></td>
                            <td class="text-sm text-ink-600 dark:text-ink-400">{{ $post->category?->name ?? '—' }}</td>
                            <td class="text-sm text-ink-600 dark:text-ink-400">{{ number_format($post->view_count) }}</td>
                            <td class="text-sm text-ink-500 whitespace-nowrap">{{ ($post->published_at ?? $post->created_at)?->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    @if($post->status === 'published')
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener noreferrer" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5 transition-colors" title="Lihat di website">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5 transition-colors" title="Edit artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" data-confirm-delete="Yakin hapus artikel '{{ Str::limit($post->title, 40) }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Hapus artikel">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-admin.empty-state title="Belum ada artikel" :action="['url' => route('admin.posts.create'), 'label' => 'Tulis artikel pertama']" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-admin.pagination :paginator="$posts" />
    </div>
</div>
@endsection
