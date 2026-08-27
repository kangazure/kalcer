@extends('layouts.app')
@push('seo_title')Galeri Kegiatan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Dokumentasi kegiatan, instalasi, dan event PT Jaringan Teknologi Sejahtera.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Galeri</span>
        <h1 class="page-hero-title" data-aos="fade-up">Dokumentasi Kegiatan Kami</h1>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($galleries as $index => $gallery)
                <a href="{{ route('gallery.show', $gallery->slug) }}" class="group relative rounded-2xl overflow-hidden h-64 block hover-card" data-aos="fade-up" data-aos-delay="{{ min($index * 60, 240) }}">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand/20 to-ink-800">
                        @if($gallery->cover_image_url)
                            <img src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-ink-950/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-5">
                        <span class="badge-brand text-xs mb-2 inline-block">{{ $gallery->category }}</span>
                        <h2 class="font-semibold text-white group-hover:text-brand-100 transition-colors">{{ $gallery->title }}</h2>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 text-ink-400">Belum ada album galeri.</div>
            @endforelse
        </div>

        @if($galleries->hasPages())
            <div class="flex items-center justify-center gap-1 pt-12">
                <a href="{{ $galleries->previousPageUrl() ?? '#' }}" class="pagination-link {{ !$galleries->previousPageUrl() ? 'disabled' : '' }}">←</a>
                @foreach($galleries->getUrlRange(1, $galleries->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link {{ $page == $galleries->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                <a href="{{ $galleries->nextPageUrl() ?? '#' }}" class="pagination-link {{ !$galleries->nextPageUrl() ? 'disabled' : '' }}">→</a>
            </div>
        @endif
    </div>
</section>
@endsection
