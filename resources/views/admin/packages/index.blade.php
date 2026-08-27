@extends('layouts.admin')
@section('page_title', 'Paket Internet')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Paket Internet</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Paket Internet</h1><p class="text-sm text-ink-500 mt-0.5">{{ $packages->count() }} paket terdaftar</p></div>
        <a href="{{ route('admin.packages.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Paket Baru
        </a>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama Paket</th><th>Kategori</th><th>Kecepatan</th><th>Harga</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td>
                            <div class="font-medium text-ink-800 dark:text-ink-200">{{ $package->name }}</div>
                            @if($package->is_popular)<span class="badge-brand text-[10px] mt-1">Populer</span>@endif
                        </td>
                        <td><span class="badge-blue text-xs">{{ str_replace('_', ' ', ucfirst($package->category)) }}</span></td>
                        <td class="text-sm text-ink-600 dark:text-ink-400">{{ $package->speed_mbps_download }} / {{ $package->speed_mbps_upload }} Mbps</td>
                        <td class="text-sm">
                            <span class="font-semibold text-ink-800 dark:text-ink-200">Rp {{ number_format((float)($package->price_promo ?? $package->price), 0, ',', '.') }}</span>
                            @if($package->price_promo)<span class="text-ink-400 line-through text-xs ml-1">Rp {{ number_format((float)$package->price, 0, ',', '.') }}</span>@endif
                        </td>
                        <td><span class="badge {{ $package->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }}">{{ $package->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.packages.edit', $package->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                <form method="POST" action="{{ route('admin.packages.destroy', $package->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" data-confirm-delete="Hapus paket '{{ $package->name }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada paket internet" :action="['url' => route('admin.packages.create'), 'label' => 'Tambah paket pertama']" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
