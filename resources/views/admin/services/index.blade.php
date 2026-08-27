@extends('layouts.admin')
@section('page_title', 'Layanan')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Layanan</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Layanan</h1><p class="text-sm text-ink-500 mt-0.5">{{ $services->count() }} layanan</p></div>
        <a href="{{ route('admin.services.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Layanan Baru
        </a>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama Layanan</th><th>Slug</th><th>Featured Home</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200">{{ $service->name }}</td>
                        <td class="text-ink-500 font-mono text-xs">{{ $service->slug }}</td>
                        <td>@if($service->is_featured_home)<span class="badge-brand text-xs">Ya</span>@else<span class="text-ink-400 text-xs">-</span>@endif</td>
                        <td><span class="badge {{ $service->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }}">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('services.show', $service->slug) }}" target="_blank" rel="noopener noreferrer" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Lihat di website">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus layanan '{{ $service->name }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><x-admin.empty-state title="Belum ada layanan" :action="['url' => route('admin.services.create'), 'label' => 'Tambah layanan pertama']" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
