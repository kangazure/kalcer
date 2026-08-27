@extends('layouts.admin')
@section('page_title', 'Manajemen Pengguna')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Pengguna</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Pengguna Admin</h1><p class="text-sm text-ink-500 mt-0.5">{{ $users->count() }} pengguna</p></div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Pengguna Baru
        </a>
    </div>
    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Login Terakhir</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-brand/10 flex items-center justify-center shrink-0 text-xs font-bold text-brand">{{ substr($user->name,0,1) }}</div>
                                <span class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-sm text-ink-500">{{ $user->email }}</td>
                        <td><span class="badge-blue text-xs">{{ $user->role?->name ?? '—' }}</span></td>
                        <td><span class="badge {{ $user->status==='active'?'badge-green':'bg-ink-100 text-ink-400' }} text-xs">{{ ucfirst($user->status) }}</span></td>
                        <td class="text-sm text-ink-400">{{ $user->last_login_at?->diffForHumans() ?? 'Belum pernah' }}</td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" data-confirm-delete="Hapus pengguna '{{ $user->name }}'? Tindakan ini permanen." class="p-1.5 text-ink-400 hover:text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada pengguna" /></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
