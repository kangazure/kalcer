@extends('layouts.admin')
@section('page_title', 'Portfolio')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Portfolio</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Portfolio</h1><p class="text-sm text-ink-500 mt-0.5">{{ $portfolio->total() }} proyek</p></div>
        <a href="{{ route('admin.portfolio.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Portfolio Baru
        </a>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Klien</th><th>Tahun</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($portfolio as $item)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200">
                            {{ $item->title }}
                            @if($item->is_featured)<span class="badge-brand text-[10px] ml-1">Featured</span>@endif
                        </td>
                        <td><span class="badge-blue text-xs">{{ $item->category }}</span></td>
                        <td class="text-sm text-ink-500">{{ $item->client_name ?? '—' }}</td>
                        <td class="text-sm text-ink-500">{{ $item->project_year }}</td>
                        <td><span class="badge {{ $item->is_published ? 'badge-green' : 'bg-ink-100 text-ink-400' }}">{{ $item->is_published ? 'Published' : 'Draft' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.portfolio.edit', $item->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                <form method="POST" action="{{ route('admin.portfolio.destroy', $item->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus portfolio '{{ $item->title }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada portfolio" :action="['url' => route('admin.portfolio.create'), 'label' => 'Tambah portfolio pertama']" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$portfolio" />
    </div>
</div>
@endsection
