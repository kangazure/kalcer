@extends('layouts.app')
@push('seo_title'){{ $service->seo_title ?? $service->name.' — PT Jaringan Teknologi Sejahtera' }}@endpush
@push('seo_description'){{ $service->seo_description ?? $service->short_description }}@endpush
@push('og_image'){{ $service->cover_image_url ?? asset('images/og/default-og.jpg') }}@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-ink-500 mb-6" aria-label="Breadcrumb" data-aos="fade-down">
            <a href="{{ route('services.index') }}" class="hover:text-brand">Layanan</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="text-ink-300">{{ $service->name }}</span>
        </nav>
        <div class="max-w-3xl">
            <span class="section-label" data-aos="fade-up">Layanan</span>
            <h1 class="page-hero-title mb-4" data-aos="fade-up" data-aos-delay="100">{{ $service->name }}</h1>
            <p class="page-hero-subtitle" data-aos="fade-up" data-aos-delay="150">{{ $service->short_description }}</p>
        </div>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                @if($service->cover_image_url)
                    <img src="{{ $service->cover_image_url }}" alt="{{ $service->name }}" class="w-full h-64 sm:h-80 object-cover rounded-2xl mb-8" data-aos="fade-up">
                @endif

                <div class="prose-jts" data-aos="fade-up" data-aos-delay="100">
                    {!! \Illuminate\Support\Str::markdown($service->description ?? '') !!}
                </div>

                @if(!empty($service->benefits))
                <div class="mt-12" data-aos="fade-up">
                    <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-5">Manfaat untuk Anda</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($service->benefits as $benefit)
                            <div class="flex gap-3 items-start p-4 bg-surface-soft dark:bg-ink-900 rounded-xl">
                                <svg class="w-5 h-5 text-brand shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-sm text-ink-700 dark:text-ink-300">{{ $benefit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <aside class="space-y-6">
                @if(!empty($service->features))
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 sticky top-24" data-aos="fade-up">
                    <h3 class="font-semibold text-ink-900 dark:text-white mb-4">Fitur Utama</h3>
                    <ul class="space-y-3 mb-6">
                        @foreach($service->features as $feature)
                            <li class="flex gap-2.5 items-start text-sm text-ink-600 dark:text-ink-300">
                                <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('contact.index') }}?layanan={{ $service->slug }}" class="block w-full text-center py-3 bg-brand hover:bg-brand-600 text-white font-semibold rounded-xl transition-all hover:shadow-glow">
                        Konsultasi Sekarang
                    </a>
                    <a href="https://wa.me/{{ config('services.whatsapp.admin_number') }}" target="_blank" rel="noopener noreferrer" class="block w-full text-center py-3 mt-2 border border-ink-200 dark:border-ink-700 text-ink-700 dark:text-ink-300 font-semibold rounded-xl hover:border-brand hover:text-brand transition-all">
                        Chat WhatsApp
                    </a>
                </div>
                @endif
            </aside>
        </div>

        @if($relatedServices->isNotEmpty())
        <div class="mt-20 pt-16 border-t border-ink-100 dark:border-ink-800">
            <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-8" data-aos="fade-up">Layanan Lainnya</h2>
            <div class="grid sm:grid-cols-3 gap-6">
                @foreach($relatedServices as $index => $related)
                    <a href="{{ route('services.show', $related->slug) }}" class="group glass-card dark:border-ink-700 rounded-2xl p-6 hover:shadow-glow transition-all hover:-translate-y-1" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                        <h3 class="font-semibold text-ink-900 dark:text-white mb-2 group-hover:text-brand transition-colors">{{ $related->name }}</h3>
                        <p class="text-sm text-ink-500 dark:text-ink-400 line-clamp-2">{{ $related->short_description }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
