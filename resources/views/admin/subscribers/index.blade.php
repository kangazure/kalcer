@extends('layouts.admin')
@section('page_title', 'Subscriber Newsletter')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Subscriber</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Subscriber Newsletter</h1><p class="text-sm text-ink-500 mt-0.5">{{ $subscribers->total() }} subscriber</p></div>
        <a href="{{ route('admin.subscribers.export') }}" class="btn-outline btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export CSV
        </a>
    </div>
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Email</th><th>Nama</th><th>Sumber</th><th>Status</th><th>Tanggal Daftar</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($subscribers as $sub)
                    <tr>
                        <td class="text-sm text-ink-700 dark:text-ink-300">{{ $sub->email }}</td>
                        <td class="text-sm text-ink-500">{{ $sub->name ?? '—' }}</td>
                        <td><span class="badge-blue text-xs">{{ $sub->source }}</span></td>
                        <td><span class="badge {{ $sub->is_verified?'badge-green':'badge-amber' }} text-xs">{{ $sub->is_verified?'Terverifikasi':'Belum Verifikasi' }}</span></td>
                        <td class="text-sm text-ink-400">{{ $sub->subscribed_at->format('d M Y') }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('admin.subscribers.destroy', $sub->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" data-confirm-delete="Hapus subscriber '{{ $sub->email }}'?" class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada subscriber" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$subscribers" />
    </div>
</div>
@endsection
