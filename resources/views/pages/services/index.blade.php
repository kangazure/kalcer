@extends('layouts.app')
@push('seo_title')Layanan Internet & Solusi Jaringan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Layanan internet rumah, bisnis, dedicated, metro ethernet, fiber optik, cloud, data center, colocation, dan managed service dari JTS.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Layanan Kami</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Solusi Konektivitas untuk Setiap Kebutuhan</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Dari rumah tangga hingga korporat, JTS menghadirkan layanan internet dan infrastruktur jaringan yang sesuai dengan kebutuhan Anda.
        </p>
    </div>
</section>

<section class="py-20 bg-surface-soft dark:bg-ink-900">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $index => $service)
                <a href="{{ route('services.show', $service->slug) }}"
                   class="group glass-card dark:border-ink-700 rounded-2xl p-6 hover:shadow-glow transition-all duration-500 hover:-translate-y-1 block"
                   data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                    <div class="w-12 h-12 rounded-xl bg-brand/10 flex items-center justify-center mb-4 group-hover:bg-brand/20 transition-colors">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-ink-900 dark:text-white mb-2 group-hover:text-brand transition-colors">{{ $service->name }}</h2>
                    <p class="text-sm text-ink-500 dark:text-ink-400 leading-relaxed mb-4">{{ $service->short_description }}</p>
                    <span class="inline-flex items-center gap-1 text-sm text-brand font-medium group-hover:gap-2 transition-all">
                        Pelajari lebih lanjut
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 bg-gradient-to-br from-ink-950 via-brand/10 to-ink-950 text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-radial-glow opacity-20" aria-hidden="true"></div>
    <div class="relative max-w-2xl mx-auto px-4">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4" data-aos="fade-up">Butuh Solusi Khusus untuk Bisnis Anda?</h2>
        <p class="text-ink-300 mb-8" data-aos="fade-up" data-aos-delay="100">Tim kami siap membantu merancang solusi jaringan yang sesuai dengan kebutuhan spesifik perusahaan Anda.</p>
        <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-brand hover:bg-brand-600 text-white font-semibold rounded-2xl transition-all hover:shadow-glow-lg" data-aos="fade-up" data-aos-delay="200">
            Konsultasi Gratis
        </a>
    </div>
</section>
@endsection
