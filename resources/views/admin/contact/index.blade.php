@extends('layouts.admin')
@section('page_title', 'Pesan Kontak')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Pesan Kontak</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Pesan Kontak</h1><p class="text-sm text-ink-500 mt-0.5">{{ $contacts->total() }} pesan</p></div>
        <div class="flex gap-2">
            @foreach(['new'=>'Baru','in_progress'=>'Diproses','resolved'=>'Selesai'] as $val=>$label)
                <a href="{{ route('admin.contact.index', ['status'=>$val]) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request('status')===$val ? 'bg-brand text-white' : 'bg-white dark:bg-ink-900 text-ink-500 border border-ink-200 dark:border-ink-700' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 overflow-hidden">
        <table class="data-table">
            <thead><tr><th>Nama</th><th>Kontak</th><th>Subjek</th><th>Status</th><th>Tanggal</th><th class="text-right">Aksi</th></tr></thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $contact->name }}</td>
                        <td class="text-sm text-ink-500">{{ $contact->email }}<br>{{ $contact->phone }}</td>
                        <td class="text-sm text-ink-500 max-w-xs truncate">{{ $contact->subject ?? $contact->message }}</td>
                        <td><span class="badge text-xs {{ match($contact->status){'new'=>'badge-brand','in_progress'=>'badge-amber','resolved'=>'badge-green',default=>'bg-ink-100 text-ink-400'} }}">{{ ucfirst(str_replace('_',' ',$contact->status)) }}</span></td>
                        <td class="text-xs text-ink-400">{{ $contact->created_at->diffForHumans() }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.contact.show', $contact->id) }}" class="p-1.5 text-ink-400 hover:text-brand rounded-lg hover:bg-brand/5" title="Detail"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-admin.empty-state title="Belum ada pesan masuk" /></td></tr>
                @endforelse
            </tbody>
        </table>
        <x-admin.pagination :paginator="$contacts" />
    </div>
</div>
@endsection
