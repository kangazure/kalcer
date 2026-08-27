@extends('layouts.app')
@push('seo_title')Karir — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Bergabunglah dengan tim PT Jaringan Teknologi Sejahtera. Lihat lowongan kerja yang tersedia saat ini.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Karir</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Bergabung Bersama Tim JTS</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Kami mencari individu yang bersemangat untuk membangun masa depan konektivitas digital di Lampung.
        </p>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            @forelse($careers as $index => $career)
                <a href="{{ route('career.show', $career->slug) }}" class="group flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 glass-card dark:border-ink-700 rounded-2xl hover:shadow-glow transition-all hover:-translate-y-0.5" data-aos="fade-up" data-aos-delay="{{ min($index * 60, 240) }}">
                    <div>
                        <h2 class="font-bold text-ink-900 dark:text-white mb-1 group-hover:text-brand transition-colors">{{ $career->title }}</h2>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-ink-500 dark:text-ink-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $career->location }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ str_replace('_', ' ', ucfirst($career->job_type)) }}
                            </span>
                            @if($career->department)
                                <span class="badge-blue text-xs">{{ $career->department }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand/10 text-brand font-semibold text-sm rounded-xl group-hover:bg-brand group-hover:text-white transition-all shrink-0">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
            @empty
                <div class="text-center py-16 text-ink-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-ink-200 dark:text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <p>Belum ada lowongan yang dibuka saat ini. Silakan cek kembali nanti.</p>
                </div>
            @endforelse
        </div>
        @if($careers->hasPages())
            <div class="flex items-center justify-center gap-1 pt-10">
                <a href="{{ $careers->previousPageUrl() ?? '#' }}" class="pagination-link {{ !$careers->previousPageUrl() ? 'disabled' : '' }}">←</a>
                @foreach($careers->getUrlRange(1, $careers->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $page == $careers->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                <a href="{{ $careers->nextPageUrl() ?? '#' }}" class="pagination-link {{ !$careers->nextPageUrl() ? 'disabled' : '' }}">→</a>
            </div>
        @endif
    </div>
</section>
@endsection
