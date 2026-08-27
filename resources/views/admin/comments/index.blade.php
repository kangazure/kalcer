@extends('layouts.admin')
@section('page_title', 'Moderasi Komentar')
@section('breadcrumb')<span class="text-ink-700 dark:text-ink-300">Komentar</span>@endsection

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h1 class="text-xl font-bold text-ink-900 dark:text-white">Moderasi Komentar</h1><p class="text-sm text-ink-500 mt-0.5">{{ $comments->total() }} komentar</p></div>
        <div class="flex gap-2">
            @foreach(['pending' => 'Pending', 'approved' => 'Disetujui', 'spam' => 'Spam'] as $val => $label)
                <a href="{{ route('admin.comments.index', ['status' => $val]) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request('status') === $val ? 'bg-brand text-white' : 'bg-white dark:bg-ink-900 text-ink-500 border border-ink-200 dark:border-ink-700' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="space-y-3">
        @forelse($comments as $comment)
            <div class="bg-white dark:bg-ink-900 rounded-2xl border border-ink-100 dark:border-ink-800 p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex gap-3 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-ink-100 dark:bg-ink-800 flex items-center justify-center shrink-0 text-sm font-bold text-ink-500">{{ substr($comment->user?->name ?? $comment->guest_name ?? '?', 0, 1) }}</div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $comment->user?->name ?? $comment->guest_name }}</span>
                                <span class="badge text-[10px] {{ $comment->status === 'approved' ? 'badge-green' : ($comment->status === 'spam' ? 'badge-red' : 'badge-amber') }}">{{ ucfirst($comment->status) }}</span>
                                <span class="text-xs text-ink-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-ink-600 dark:text-ink-400 mt-1">{{ $comment->content }}</p>
                            <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" rel="noopener noreferrer" class="text-xs text-brand hover:underline mt-1.5 inline-block">Pada artikel: {{ Str::limit($comment->post->title, 50) }}</a>
                        </div>
                    </div>
                    <div class="flex gap-1.5 shrink-0">
                        @if($comment->status !== 'approved')
                            <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-1.5 text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg" title="Setujui"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                            </form>
                        @endif
                        @if($comment->status !== 'spam')
                            <form method="POST" action="{{ route('admin.comments.spam', $comment->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="p-1.5 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg" title="Tandai Spam"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg></button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" data-confirm-delete="Hapus komentar ini?" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <x-admin.empty-state title="Tidak ada komentar" description="Semua komentar sudah dimoderasi" />
        @endforelse
    </div>

    <x-admin.pagination :paginator="$comments" />
</div>
@endsection
