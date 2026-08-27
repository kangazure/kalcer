@props(['paginator'])

@if($paginator->hasPages())
    <div class="px-4 py-3 border-t border-ink-100 dark:border-ink-800 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-xs text-ink-400">Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</p>
        <div class="flex items-center gap-1">
            <a href="{{ $paginator->previousPageUrl() ?? '#' }}" class="pagination-link {{ !$paginator->previousPageUrl() ? 'disabled' : '' }}" aria-label="Halaman sebelumnya">←</a>
            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="pagination-link {{ $page == $paginator->currentPage() ? 'active' : '' }}" @if($page == $paginator->currentPage()) aria-current="page" @endif aria-label="Halaman {{ $page }}">{{ $page }}</a>
            @endforeach
            <a href="{{ $paginator->nextPageUrl() ?? '#' }}" class="pagination-link {{ !$paginator->nextPageUrl() ? 'disabled' : '' }}" aria-label="Halaman berikutnya">→</a>
        </div>
    </div>
@endif
