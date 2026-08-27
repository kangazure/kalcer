@extends('layouts.app')
@push('seo_title')Paket Internet — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Pilihan paket internet rumah, bisnis, dedicated, dan metro ethernet dari JTS. Unlimited tanpa FUP, kecepatan simetris, instalasi gratis.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Paket Internet</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Pilih Paket yang Sesuai Kebutuhan Anda</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Semua paket unlimited tanpa FUP, kecepatan simetris (upload = download), dan instalasi gratis.
        </p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950" x-data="{ activeTab: 'home' }">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tab kategori --}}
        <div class="flex flex-wrap justify-center gap-2 mb-14" data-aos="fade-up">
            @php
                $categoryLabels = ['home' => 'Internet Rumah', 'business' => 'Internet Bisnis', 'dedicated' => 'Dedicated', 'metro_ethernet' => 'Metro Ethernet', 'enterprise' => 'Enterprise'];
            @endphp
            @foreach($packagesByCategory as $category => $items)
                <button @click="activeTab = '{{ $category }}'"
                        :class="activeTab === '{{ $category }}' ? 'bg-brand text-white shadow-glow' : 'bg-surface-soft dark:bg-ink-900 text-ink-600 dark:text-ink-400'"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium transition-all">
                    {{ $categoryLabels[$category] ?? ucfirst($category) }}
                </button>
            @endforeach
        </div>

        @foreach($packagesByCategory as $category => $items)
            <div x-show="activeTab === '{{ $category }}'" x-cloak x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($items as $index => $package)
                    <article class="relative rounded-2xl border overflow-hidden transition-all duration-500 hover:-translate-y-2
                        {{ $package->is_popular ? 'border-brand bg-gradient-to-b from-brand/5 to-transparent shadow-glow' : 'border-ink-200 dark:border-ink-700 bg-white dark:bg-ink-900' }}"
                         data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                        @if($package->is_popular)
                            <div class="absolute top-0 left-0 right-0 py-1.5 text-center text-xs font-bold text-white bg-brand uppercase tracking-wider">Paling Populer</div>
                        @endif
                        <div class="p-6 {{ $package->is_popular ? 'pt-10' : '' }}">
                            <h3 class="font-bold text-ink-900 dark:text-white text-lg mb-1">{{ $package->name }}</h3>
                            <p class="text-sm text-ink-500 dark:text-ink-400 mb-4">{{ $package->service?->name ?? $categoryLabels[$category] ?? '' }}</p>
                            <div class="flex items-baseline gap-1 mb-2">
                                <span class="text-3xl font-bold {{ $package->is_popular ? 'text-brand' : 'text-ink-900 dark:text-white' }}">
                                    Rp {{ number_format((float) $package->effective_price, 0, ',', '.') }}
                                </span>
                                <span class="text-ink-400 text-sm">/{{ $package->billing_cycle === 'monthly' ? 'bulan' : $package->billing_cycle }}</span>
                            </div>
                            @if($package->has_promo)
                                <p class="text-xs text-ink-400 line-through mb-4">Rp {{ number_format((float) $package->price, 0, ',', '.') }}</p>
                            @endif
                            <div class="py-4 border-y border-ink-100 dark:border-ink-800 mb-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <svg class="w-5 h-5 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span class="text-ink-700 dark:text-ink-200 font-semibold">{{ $package->speed_mbps_download }} / {{ $package->speed_mbps_upload }} Mbps</span>
                                </div>
                                <p class="text-xs text-ink-500 ml-8">Kecepatan simetris · {{ $package->is_unlimited ? 'Unlimited tanpa FUP' : 'FUP '.$package->fup_gb.' GB' }}</p>
                            </div>
                            <ul class="space-y-2 mb-6">
                                @foreach($package->features ?? [] as $feature)
                                    <li class="flex items-center gap-2 text-sm text-ink-600 dark:text-ink-300">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                            @if($package->installation_fee > 0)
                                <p class="text-xs text-ink-400 mb-3">Biaya instalasi: Rp {{ number_format((float)$package->installation_fee, 0, ',', '.') }}</p>
                            @else
                                <p class="text-xs text-green-600 dark:text-green-400 mb-3">✓ Instalasi Gratis</p>
                            @endif
                            <a href="{{ route('contact.index') }}?paket={{ $package->slug }}"
                               class="block w-full text-center py-3 rounded-xl font-semibold text-sm transition-all duration-300
                               {{ $package->is_popular ? 'bg-brand hover:bg-brand-600 text-white shadow-glow hover:shadow-glow-lg' : 'border border-brand text-brand hover:bg-brand hover:text-white' }}">
                                Daftar Sekarang
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<section class="py-16 bg-surface-soft dark:bg-ink-900 text-center">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-3" data-aos="fade-up">Belum yakin paket mana yang cocok?</h2>
        <p class="text-ink-500 dark:text-ink-400 mb-6" data-aos="fade-up" data-aos-delay="100">Tim kami siap membantu Anda memilih paket yang tepat sesuai kebutuhan.</p>
        <a href="{{ route('contact.index') }}" class="btn-primary" data-aos="fade-up" data-aos-delay="200">Konsultasi Gratis</a>
    </div>
</section>
@endsection
