@extends('layouts.app')

@push('seo_title')PT Jaringan Teknologi Sejahtera — Internet Fiber Optik Terpercaya Lampung Timur & Tengah@endpush
@push('seo_description')JTS menghadirkan internet fiber optik GPON tercepat, stabil, dan terjangkau untuk rumah, bisnis, dan korporat. Melayani Raman Utara, Way Bungur, Purbolinggo, Seputih Banyak, dan Kota Gajah. Cek jangkauan sekarang.@endpush
@push('og_title')PT JTS — Internet Fiber Optik Terpercaya@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden bg-ink-950" aria-label="Hero">
    <canvas id="hero-canvas" class="absolute inset-0 w-full h-full pointer-events-none" aria-hidden="true"></canvas>
    <div class="absolute inset-0 bg-gradient-to-br from-ink-950 via-ink-900/80 to-ink-950" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-radial-glow opacity-30" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.04]" aria-hidden="true" style="background-size: 40px 40px;"></div>

    <div class="relative z-10 max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-brand/10 border border-brand/20 rounded-full text-brand text-sm font-medium mb-8 hero-badge" data-aos="fade-down">
            <span class="w-2 h-2 rounded-full bg-brand animate-pulse" aria-hidden="true"></span>
            Anggota APJII Lampung · ISP Terpercaya Sejak 2024
        </div>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-tight mb-6 hero-heading" data-aos="fade-up" data-aos-delay="100">
            Internet Fiber Optik
            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-brand-red via-brand to-brand-400 animate-gradient-shift" style="background-size: 200% auto;">
                Tercepat &amp; Terpercaya
            </span>
        </h1>

        <p class="text-lg sm:text-xl text-ink-300 max-w-2xl mx-auto mb-10 leading-relaxed hero-subtitle" data-aos="fade-up" data-aos-delay="200">
            Teknologi GPON fiber optik, kecepatan hingga <strong class="text-white">100 Mbps simetris</strong>,
            unlimited tanpa FUP, instalasi gratis. Untuk <span class="text-brand font-medium" id="typing-text">rumah Anda.</span>
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-14" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('coverage.index') }}"
               class="group inline-flex items-center gap-3 px-8 py-4 bg-brand hover:bg-brand-500 text-white font-semibold text-base rounded-2xl transition-all duration-300 hover:shadow-glow-lg hover:scale-105 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Cek Jangkauan Gratis
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
            <a href="{{ route('packages.index') }}"
               class="inline-flex items-center gap-3 px-8 py-4 glass-card hover:bg-white/10 text-white font-semibold text-base rounded-2xl transition-all duration-300 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Lihat Paket Internet
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="400">
            @foreach([
                ['value' => '500', 'suffix' => '+', 'label' => 'Pelanggan Aktif'],
                ['value' => '5', 'suffix' => '', 'label' => 'Titik POP Aktif'],
                ['value' => '99.9', 'suffix' => '%', 'label' => 'Network Uptime'],
                ['value' => '24', 'suffix' => '/7', 'label' => 'Dukungan Teknis'],
            ] as $stat)
                <div class="glass-card rounded-2xl p-4 text-center border border-white/5">
                    <div class="text-2xl lg:text-3xl font-bold text-white mb-1">
                        <span class="counter" data-target="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] }}
                    </div>
                    <div class="text-xs text-ink-400">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce" aria-hidden="true">
        <div class="w-6 h-10 border-2 border-ink-600 rounded-full flex justify-center pt-2">
            <div class="w-1 h-2.5 bg-brand rounded-full animate-float"></div>
        </div>
    </div>
</section>

{{-- ═══ LAYANAN ═══ --}}
@if($services && $services->isNotEmpty())
<section class="py-24 bg-surface-soft dark:bg-ink-900 relative overflow-hidden" aria-label="Layanan Unggulan">
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.02]" aria-hidden="true" style="background-size: 32px 32px;"></div>
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="section-label">Layanan Kami</span>
            <h2 class="section-title mb-4">Solusi Internet untuk Setiap Kebutuhan</h2>
            <p class="section-subtitle mx-auto">Dari internet rumah hingga dedicated enterprise, JTS hadir dengan solusi yang tepat untuk Anda.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services->take(6) as $index => $service)
                <article class="group relative glass-card dark:border-ink-700 rounded-2xl p-6 hover:shadow-glow transition-all duration-500 hover:-translate-y-1"
                         data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                    <div class="w-12 h-12 rounded-xl bg-brand/10 flex items-center justify-center mb-4 group-hover:bg-brand/20 transition-colors">
                        <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-ink-900 dark:text-white mb-2 group-hover:text-brand transition-colors">{{ $service->name }}</h3>
                    <p class="text-sm text-ink-500 dark:text-ink-400 leading-relaxed mb-4">{{ $service->short_description }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="inline-flex items-center gap-1 text-sm text-brand font-medium hover:gap-2 transition-all">
                        Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </article>
            @endforeach
        </div>
        <div class="text-center mt-10" data-aos="fade-up">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-6 py-3 border border-brand/30 text-brand hover:bg-brand hover:text-white rounded-xl font-medium transition-all duration-300">
                Semua Layanan
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══ PAKET POPULER ═══ --}}
@if($popularPackages && $popularPackages->isNotEmpty())
<section class="py-24 bg-white dark:bg-ink-950 relative" aria-label="Paket Terpopuler">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="section-label">Paket Internet</span>
            <h2 class="section-title mb-4">Pilihan Paket Terpopuler</h2>
            <p class="section-subtitle mx-auto">Semua paket unlimited tanpa FUP, kecepatan simetris, dan instalasi gratis.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($popularPackages as $index => $package)
                <article class="relative rounded-2xl border overflow-hidden transition-all duration-500 hover:-translate-y-2
                    {{ $package->is_popular ? 'border-brand bg-gradient-to-b from-brand/5 to-transparent shadow-glow' : 'border-ink-200 dark:border-ink-700 bg-white dark:bg-ink-900' }}"
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    @if($package->is_popular)
                        <div class="absolute top-0 left-0 right-0 py-1.5 text-center text-xs font-bold text-white bg-brand uppercase tracking-wider">Paling Populer</div>
                    @endif
                    <div class="p-6 {{ $package->is_popular ? 'pt-10' : '' }}">
                        <h3 class="font-bold text-ink-900 dark:text-white text-lg mb-1">{{ $package->name }}</h3>
                        <p class="text-sm text-ink-500 dark:text-ink-400 mb-4">{{ $package->service?->name }}</p>
                        <div class="flex items-baseline gap-1 mb-2">
                            <span class="text-3xl font-bold {{ $package->is_popular ? 'text-brand' : 'text-ink-900 dark:text-white' }}">
                                Rp {{ number_format((float) $package->effective_price, 0, ',', '.') }}
                            </span>
                            <span class="text-ink-400 text-sm">/bulan</span>
                        </div>
                        @if($package->has_promo)
                            <p class="text-xs text-ink-400 line-through mb-4">Rp {{ number_format((float) $package->price, 0, ',', '.') }}</p>
                        @endif
                        <div class="py-4 border-y border-ink-100 dark:border-ink-800 mb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-ink-700 dark:text-ink-200 font-semibold">{{ $package->speed_mbps_download }} Mbps</span>
                            </div>
                            <p class="text-xs text-ink-500 ml-8">Kecepatan simetris (up = down)</p>
                        </div>
                        <ul class="space-y-2 mb-6">
                            @foreach(array_slice($package->features ?? [], 0, 4) as $feature)
                                <li class="flex items-center gap-2 text-sm text-ink-600 dark:text-ink-300">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('contact.index') }}?paket={{ $package->slug }}"
                           class="block w-full text-center py-3 rounded-xl font-semibold text-sm transition-all duration-300
                           {{ $package->is_popular ? 'bg-brand hover:bg-brand-600 text-white shadow-glow hover:shadow-glow-lg' : 'border border-brand text-brand hover:bg-brand hover:text-white' }}">
                            Daftar Sekarang
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="text-center mt-10" data-aos="fade-up">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-brand font-medium hover:underline">
                Lihat semua paket internet
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══ MENGAPA JTS ═══ --}}
<section class="py-24 bg-ink-950 relative overflow-hidden" aria-label="Mengapa JTS">
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]" aria-hidden="true" style="background-size: 40px 40px;"></div>
    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-brand/5 blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="section-label">Mengapa JTS?</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6 leading-tight">Infrastruktur Modern, Layanan Lokal yang Responsif</h2>
                <p class="text-ink-400 leading-relaxed mb-8">JTS menggabungkan teknologi fiber optik terdepan dengan pemahaman mendalam tentang kebutuhan komunitas Lampung, menghadirkan pengalaman internet yang berbeda dari provider nasional.</p>
                <div class="space-y-4">
                    @foreach([
                        ['title' => 'Teknologi GPON Terkini', 'desc' => 'Infrastruktur fiber optik generasi terbaru dengan kapasitas hingga 2.5 Gbps per OLT.'],
                        ['title' => 'Tim Lokal Responsif', 'desc' => 'Teknisi berdomisili di area layanan — respons gangguan rata-rata di bawah 4 jam.'],
                        ['title' => 'Uptime 99.9% Terbukti', 'desc' => 'Monitoring NOC 24/7 dengan sistem redundansi backbone berlapis untuk keandalan maksimal.'],
                        ['title' => 'Harga Transparan', 'desc' => 'Tidak ada biaya tersembunyi. Tagihan sesuai paket, tanpa trik kecepatan setelah batas tertentu.'],
                    ] as $i => $feature)
                        <div class="flex gap-4" data-aos="fade-right" data-aos-delay="{{ $i * 100 }}">
                            <div class="w-8 h-8 rounded-lg bg-brand/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-sm mb-0.5">{{ $feature['title'] }}</h3>
                                <p class="text-ink-400 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div data-aos="fade-left" data-aos-delay="200">
                <div class="glass-card rounded-3xl p-6 border border-white/5">
                    <div class="flex items-center justify-between mb-5">
                        <span class="text-white font-semibold text-sm">Status Jaringan Real-time</span>
                        <span class="flex items-center gap-1.5 text-green-400 text-xs font-medium">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse" aria-hidden="true"></span>
                            Semua Sistem Online
                        </span>
                    </div>
                    <div class="space-y-3" aria-live="polite" aria-label="Status node jaringan">
                        @forelse($networkStatus as $node)
                            <div class="flex items-center justify-between py-2.5 px-3 bg-ink-900/60 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $node->status === 'online' ? 'bg-green-500' : ($node->status === 'degraded' ? 'bg-amber-500' : 'bg-red-500') }}" aria-hidden="true"></span>
                                    <span class="text-ink-300 text-sm">{{ $node->node_name }}</span>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $node->status === 'online' ? 'bg-green-500/20 text-green-400' : ($node->status === 'degraded' ? 'bg-amber-500/20 text-amber-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ ucfirst($node->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-ink-500 text-sm py-4">Data status belum tersedia.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('network-status.index') }}" class="block mt-4 text-center text-xs text-ink-500 hover:text-brand transition-colors">Lihat detail status jaringan →</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ COVERAGE ═══ --}}
<section class="py-24 bg-surface-soft dark:bg-ink-900 relative overflow-hidden" aria-label="Coverage Area">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="section-label">Jangkauan Layanan</span>
                <h2 class="section-title mb-4">Apakah Lokasi Anda Sudah Terjangkau?</h2>
                <p class="section-subtitle mb-6">JTS melayani 5 titik POP di Lampung Timur dan Lampung Tengah. Cek sekarang apakah wilayah Anda sudah tersedia.</p>
                <div class="space-y-2 mb-8">
                    @foreach($coverageAreas as $area)
                        <div class="flex items-center justify-between py-2.5 px-4 bg-white dark:bg-ink-800 rounded-xl border border-ink-100 dark:border-ink-700">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full {{ $area->coverage_status === 'available' ? 'bg-green-500' : ($area->coverage_status === 'partial' ? 'bg-amber-500' : 'bg-blue-500') }}" aria-hidden="true"></span>
                                <span class="text-ink-700 dark:text-ink-300 text-sm font-medium">{{ $area->region_name }}</span>
                                <span class="text-xs text-ink-400">{{ $area->regency }}</span>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $area->coverage_status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' :
                                   ($area->coverage_status === 'partial' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' :
                                   'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400') }}">
                                {{ $area->coverage_status === 'available' ? 'Tersedia' : ($area->coverage_status === 'partial' ? 'Sebagian' : 'Segera Hadir') }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('coverage.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand hover:bg-brand-600 text-white font-semibold rounded-xl transition-all hover:shadow-glow">Cek Jangkauan Sekarang</a>
                    <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-ink-300 dark:border-ink-600 text-ink-700 dark:text-ink-300 hover:border-brand hover:text-brand font-semibold rounded-xl transition-all">Minta Ekspansi Area</a>
                </div>
            </div>
            <div class="rounded-2xl overflow-hidden shadow-2xl h-80 lg:h-[480px] bg-ink-800 flex items-center justify-center" data-aos="fade-left">
                <div id="home-coverage-map" class="w-full h-full" aria-label="Peta coverage area JTS">
                    <div class="w-full h-full flex items-center justify-center text-ink-500 text-sm">
                        <div class="text-center">
                            <svg class="w-12 h-12 mb-3 mx-auto text-ink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            Peta interaktif — aktifkan Google Maps API
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TESTIMONIAL ═══ --}}
@if($testimonials && $testimonials->isNotEmpty())
<section class="py-24 bg-white dark:bg-ink-950 overflow-hidden" aria-label="Testimoni Pelanggan">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="section-label">Testimoni</span>
            <h2 class="section-title">Apa Kata Pelanggan JTS?</h2>
        </div>
        <div class="swiper testimonial-swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper pb-12">
                @foreach($testimonials as $testimonial)
                    <div class="swiper-slide h-auto">
                        <article class="h-full glass-card dark:border-ink-700 rounded-2xl p-6 flex flex-col">
                            <div class="flex gap-1 mb-4" aria-label="Rating {{ $testimonial->rating }} bintang">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-ink-200 dark:text-ink-700' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <blockquote class="text-ink-600 dark:text-ink-400 text-sm leading-relaxed mb-5 flex-1 italic">"{{ $testimonial->content }}"</blockquote>
                            <footer class="flex items-center gap-3 pt-4 border-t border-ink-100 dark:border-ink-800">
                                <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center shrink-0" aria-hidden="true">
                                    <span class="text-brand font-bold text-sm">{{ substr($testimonial->customer_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <cite class="not-italic font-semibold text-ink-900 dark:text-white text-sm">{{ $testimonial->customer_name }}</cite>
                                    @if($testimonial->customer_role)<p class="text-xs text-ink-400">{{ $testimonial->customer_role }}</p>@endif
                                </div>
                            </footer>
                        </article>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination" aria-label="Testimonial slide pagination"></div>
        </div>
    </div>
</section>
@endif

{{-- ═══ BLOG TERBARU ═══ --}}
@if($latestPosts && $latestPosts->isNotEmpty())
<section class="py-24 bg-surface-soft dark:bg-ink-900" aria-label="Artikel Terbaru">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-12" data-aos="fade-up">
            <div>
                <span class="section-label mb-2">Blog</span>
                <h2 class="text-3xl font-bold text-ink-900 dark:text-white">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-brand font-medium hover:underline text-sm">
                Semua Artikel
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestPosts as $index => $post)
                <article class="group glass-card dark:border-ink-700 rounded-2xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    @if($post->cover_image_url)
                        <a href="{{ route('blog.show', $post->slug) }}" class="block h-44 overflow-hidden" aria-label="{{ $post->title }}">
                            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        </a>
                    @else
                        <div class="h-44 bg-gradient-to-br from-brand/20 to-ink-200 dark:to-ink-700 flex items-center justify-center" aria-hidden="true">
                            <svg class="w-12 h-12 text-brand/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="p-5">
                        @if($post->category)<span class="inline-block text-xs font-semibold text-brand uppercase tracking-wider mb-2">{{ $post->category->name }}</span>@endif
                        <h3 class="font-bold text-ink-900 dark:text-white mb-2 line-clamp-2 group-hover:text-brand transition-colors">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        @if($post->excerpt)<p class="text-sm text-ink-500 dark:text-ink-400 line-clamp-2 mb-3">{{ $post->excerpt }}</p>@endif
                        <div class="flex items-center gap-3 text-xs text-ink-400">
                            <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('d M Y') }}</time>
                            <span>·</span>
                            <span>{{ $post->reading_time_minutes }} menit baca</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══ CTA ═══ --}}
<section class="py-24 bg-gradient-to-br from-ink-950 via-brand/10 to-ink-950 relative overflow-hidden" aria-label="CTA Daftar">
    <div class="absolute inset-0 bg-radial-glow opacity-20" aria-hidden="true"></div>
    <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight" data-aos="fade-up">
            Siap Menikmati Internet<br><span class="text-brand">Fiber Optik Terbaik?</span>
        </h2>
        <p class="text-ink-300 text-lg max-w-xl mx-auto mb-10" data-aos="fade-up" data-aos-delay="100">Daftar sekarang dan nikmati internet cepat, stabil, tanpa batas. Instalasi gratis, tanpa biaya tersembunyi.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('coverage.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-10 py-4 bg-brand hover:bg-brand-500 text-white font-bold text-lg rounded-2xl transition-all hover:shadow-glow-lg hover:scale-105">Daftar Sekarang — Gratis</a>
            <a href="https://wa.me/{{ config('services.whatsapp.admin_number') }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 glass-card text-white font-semibold rounded-2xl hover:bg-white/10 transition-all">
                <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Tanya via WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const targets = ['rumah Anda.', 'bisnis Anda.', 'korporat Anda.', 'masa depan Anda.'];
    let ti = 0, ci = 0, deleting = false;
    const el = document.getElementById('typing-text');
    if (el) {
        function typeLoop() {
            const t = targets[ti];
            el.textContent = deleting ? t.substring(0, ci--) : t.substring(0, ci++);
            if (!deleting && ci > t.length) { deleting = true; setTimeout(typeLoop, 1500); return; }
            if (deleting && ci < 0) { deleting = false; ti = (ti + 1) % targets.length; ci = 0; setTimeout(typeLoop, 400); return; }
            setTimeout(typeLoop, deleting ? 60 : 100);
        }
        typeLoop();
    }

    const counters = document.querySelectorAll('.counter');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseFloat(el.dataset.target);
            const isFloat = el.dataset.target.includes('.');
            let start = 0;
            const step = target / 50;
            const timer = setInterval(() => {
                start = Math.min(start + step, target);
                el.textContent = isFloat ? start.toFixed(1) : Math.floor(start);
                if (start >= target) clearInterval(timer);
            }, 40);
            counterObserver.unobserve(el);
        });
    }, { threshold: 0.5 });
    counters.forEach(c => counterObserver.observe(c));
});
</script>
@endpush
