@extends('layouts.admin')
@section('page_title', 'Backup Database')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Backup</span>@endsection

@section('content')
<div x-data="{ running: false }" class="space-y-5 max-w-3xl">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Backup Database</h1><p class="text-sm text-ink-500 mt-0.5">Backup otomatis berjalan setiap hari pukul 01:00 WIB</p></div>
        <form method="POST" action="{{ route('admin.backup.run') }}" @submit="running = true">
            @csrf
            <button type="submit" :disabled="running" class="btn-primary btn-sm">
                <svg x-show="running" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span x-text="running ? 'Membuat backup...' : 'Jalankan Backup Sekarang'"></span>
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama File</th><th>Ukuran</th><th>Tanggal Dibuat</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($backups ?? [] as $backup)
                    <tr>
                        <td class="text-sm font-mono text-ink-700 dark:text-ink-300">{{ $backup['name'] }}</td>
                        <td class="text-sm text-ink-500">{{ $backup['size'] }}</td>
                        <td class="text-sm text-ink-400">{{ $backup['date'] }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.backup.download', $backup['path']) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Unduh">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><x-admin.empty-state title="Belum ada file backup" description="Jalankan backup pertama menggunakan tombol di atas" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="alert-info">
        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>Backup disimpan di storage S3-compatible sesuai konfigurasi <code class="text-xs bg-blue-100 dark:bg-blue-900/40 px-1 rounded">AWS_BUCKET</code> pada file .env. Backup lama otomatis dihapus setelah 30 hari.</div>
    </div>
</div>
@endsection
