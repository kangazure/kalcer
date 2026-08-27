@extends('layouts.app')
@push('seo_title')Portfolio Proyek — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Studi kasus dan proyek yang telah dikerjakan PT Jaringan Teknologi Sejahtera untuk berbagai klien di Lampung.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Portfolio</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Proyek yang Telah Kami Kerjakan</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Studi kasus nyata implementasi jaringan JTS untuk klien perumahan, industri, hingga koperasi.
        </p>
    </div>
</section>

<section class="py-20 bg-surface-soft dark:bg-ink-900">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($portfolio as $index => $item)
                <a href="{{ route('portfolio.show', $item->slug) }}" class="group glass-card dark:border-ink-700 rounded-2xl overflow-hidden hover-card block" data-aos="fade-up" data-aos-delay="{{ min($index * 60, 240) }}">
                    <div class="h-44 bg-gradient-to-br from-brand/20 to-ink-200 dark:to-ink-700 overflow-hidden">
                        @if($item->cover_image_url)
                            <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="badge-blue text-xs">{{ $item->category }}</span>
                            <span class="text-xs text-ink-400">{{ $item->project_year }}</span>
                        </div>
                        <h2 class="font-bold text-ink-900 dark:text-white mb-2 group-hover:text-brand transition-colors line-clamp-2">{{ $item->title }}</h2>
                        <p class="text-sm text-ink-500 dark:text-ink-400 line-clamp-2 mb-4">{{ $item->summary }}</p>
                        @if($item->result_metric_value)
                            <div class="flex items-baseline gap-2 pt-4 border-t border-ink-100 dark:border-ink-800">
                                <span class="text-xl font-bold text-brand">{{ $item->result_metric_value }}</span>
                                <span class="text-xs text-ink-400">{{ $item->result_metric_label }}</span>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 text-ink-400">Belum ada portfolio yang dipublikasikan.</div>
            @endforelse
        </div>

        @if($portfolio->hasPages())
            <div class="flex items-center justify-center gap-1 pt-12">
                <a href="{{ $portfolio->previousPageUrl() ?? '#' }}" class="pagination-link {{ !$portfolio->previousPageUrl() ? 'disabled' : '' }}">←</a>
                @foreach($portfolio->getUrlRange(1, $portfolio->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $page == $portfolio->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                <a href="{{ $portfolio->nextPageUrl() ?? '#' }}" class="pagination-link {{ !$portfolio->nextPageUrl() ? 'disabled' : '' }}">→</a>
            </div>
        @endif
    </div>
</section>
@endsection
