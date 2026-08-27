@props(['title' => 'Belum ada data', 'description' => null, 'action' => null])

<div class="text-center py-16 text-ink-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-ink-200 dark:text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <p class="font-medium">{{ $title }}</p>
    @if($description)
        <p class="text-sm mt-1">{{ $description }}</p>
    @endif
    @if($action)
        <a href="{{ $action['url'] }}" class="text-brand text-sm hover:underline mt-2 inline-block">{{ $action['label'] }} →</a>
    @endif
</div>
