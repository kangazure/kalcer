@extends('layouts.app')
@push('seo_title'){{ $portfolio->seo_title ?? $portfolio->title }} — Portfolio JTS@endpush
@push('seo_description'){{ $portfolio->seo_description ?? $portfolio->summary }}@endpush
@push('og_image'){{ $portfolio->cover_image_url ?? asset('images/og/default-og.jpg') }}@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-ink-500 mb-6" aria-label="Breadcrumb" data-aos="fade-down">
            <a href="{{ route('portfolio.index') }}" class="hover:text-brand">Portfolio</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="text-ink-300">{{ $portfolio->title }}</span>
        </nav>
        <div class="flex items-center gap-2 mb-4" data-aos="fade-up">
            <span class="badge-brand text-xs">{{ $portfolio->category }}</span>
            <span class="text-xs text-ink-400">{{ $portfolio->project_year }}</span>
        </div>
        <h1 class="page-hero-title mb-4" data-aos="fade-up" data-aos-delay="100">{{ $portfolio->title }}</h1>
        @if($portfolio->client_name)
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="150">Klien: {{ $portfolio->client_name }} · {{ $portfolio->location }}</p>
        @endif
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">
        @if($portfolio->cover_image_url)
            <img src="{{ $portfolio->cover_image_url }}" alt="{{ $portfolio->title }}" class="w-full h-64 sm:h-96 object-cover rounded-2xl mb-10" data-aos="fade-up">
        @endif

        @if($portfolio->result_metric_value)
        <div class="glass-card dark:border-ink-700 rounded-2xl p-6 mb-10 flex items-center gap-4" data-aos="fade-up">
            <div class="w-14 h-14 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-brand">{{ $portfolio->result_metric_value }}</div>
                <div class="text-sm text-ink-500 dark:text-ink-400">{{ $portfolio->result_metric_label }}</div>
            </div>
        </div>
        @endif

        <div class="prose-jts" data-aos="fade-up" data-aos-delay="100">
            {!! \Illuminate\Support\Str::markdown($portfolio->description ?? $portfolio->summary ?? '') !!}
        </div>

        <div class="mt-14 pt-10 border-t border-ink-100 dark:border-ink-800 text-center">
            <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-3" data-aos="fade-up">Punya Kebutuhan Serupa?</h2>
            <p class="text-ink-500 dark:text-ink-400 mb-6" data-aos="fade-up" data-aos-delay="100">Konsultasikan kebutuhan jaringan Anda dengan tim JTS.</p>
            <a href="{{ route('contact.index') }}" class="btn-primary" data-aos="fade-up" data-aos-delay="200">Hubungi Kami</a>
        </div>
    </div>
</section>
@endsection
