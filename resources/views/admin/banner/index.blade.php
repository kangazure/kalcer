@extends('layouts.admin')
@section('page_title', 'Banner & Slider')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Banner & Slider</span>@endsection

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Banner &amp; Slider</h1><p class="text-sm text-ink-500 mt-0.5">Kelola promosi visual homepage</p></div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-ink-900 dark:text-white">Hero Slider</h2>
            <a href="{{ route('admin.sliders.create') }}" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Slide Baru
            </a>
        </div>
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
            <table class="data-table">
                <thead><tr><th>Preview</th><th>Judul</th><th>Urutan</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td><img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-16 h-10 object-cover rounded-lg"></td>
                            <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $slider->title }}</td>
                            <td class="text-sm text-ink-500">{{ $slider->sort_order }}</td>
                            <td><span class="badge {{ $slider->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }} text-xs">{{ $slider->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                    <form method="POST" action="{{ route('admin.sliders.destroy', $slider->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" data-confirm-delete="Hapus slide '{{ $slider->title }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-admin.empty-state title="Belum ada slide" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ modalOpen: false, editing: null, form: { title:'', position:'home_hero', image_url:'', link_url:'', is_active:true } }">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-ink-900 dark:text-white">Banner Promosi</h2>
            <button @click="modalOpen = true; editing = null; form = { title:'', position:'home_hero', image_url:'', link_url:'', is_active:true }" class="btn-primary btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Banner Baru
            </button>
        </div>
        <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
            <table class="data-table">
                <thead><tr><th>Preview</th><th>Judul</th><th>Posisi</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td><img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-16 h-10 object-cover rounded-lg"></td>
                            <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $banner->title }}</td>
                            <td><span class="badge-blue text-xs">{{ str_replace('_',' ',$banner->position) }}</span></td>
                            <td><span class="badge {{ $banner->is_active ? 'badge-green' : 'bg-ink-100 text-ink-400' }} text-xs">{{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                    <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" data-confirm-delete="Hapus banner '{{ $banner->title }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-admin.empty-state title="Belum ada banner" :action="['url' => route('admin.banners.create'), 'label' => 'Tambah banner pertama']" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
