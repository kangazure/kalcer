@extends('layouts.app')
@push('seo_title'){{ $gallery->title }} — Galeri JTS@endpush
@push('seo_description'){{ $gallery->description ?? $gallery->title }}@endpush
@push('og_image'){{ $gallery->cover_image_url ?? asset('images/og/default-og.jpg') }}@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-ink-500 mb-6" aria-label="Breadcrumb" data-aos="fade-down">
            <a href="{{ route('gallery.index') }}" class="hover:text-brand">Galeri</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="text-ink-300">{{ $gallery->title }}</span>
        </nav>
        <span class="badge-brand text-xs mb-3 inline-block" data-aos="fade-up">{{ $gallery->category }}</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up" data-aos-delay="100">{{ $gallery->title }}</h1>
        @if($gallery->description)
            <p class="page-hero-subtitle max-w-2xl" data-aos="fade-up" data-aos-delay="150">{{ $gallery->description }}</p>
        @endif
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($photos->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($photos as $index => $photo)
                    <div class="rounded-xl overflow-hidden aspect-square hover-card" data-aos="fade-up" data-aos-delay="{{ min($index * 40, 200) }}">
                        <img src="{{ $photo->public_url }}" alt="{{ $photo->alt_text ?? $gallery->title }}" class="w-full h-full object-cover" loading="lazy">
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                @if($gallery->cover_image_url)
                    <img src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title }}" class="max-w-2xl w-full mx-auto rounded-2xl mb-6">
                @endif
                <p class="text-ink-400 text-sm">Belum ada foto tambahan pada album ini.</p>
            </div>
        @endif

        <div class="text-center mt-14">
            <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 text-brand font-medium hover:underline">
                ← Kembali ke Galeri
            </a>
        </div>
    </div>
</section>
@endsection
