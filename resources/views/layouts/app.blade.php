<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" x-data="{ darkMode: $persist(false).as('jts_darkMode') }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@stack('seo_title', config('app.name'))</title>
    <meta name="description" content="@stack('seo_description', 'PT Jaringan Teknologi Sejahtera — ISP Fiber Optik Lampung Timur')">
    <meta name="keywords" content="@stack('seo_keywords', 'internet fiber optik, ISP lampung timur, internet cepat lampung')">
    <meta name="author" content="PT Jaringan Teknologi Sejahtera">
    <meta name="robots" content="@stack('robots', 'index, follow')">
    @stack('canonical_url')

    <meta property="og:type" content="@stack('og_type', 'website')">
    <meta property="og:title" content="@stack('og_title', config('app.name'))">
    <meta property="og:description" content="@stack('og_description', 'PT Jaringan Teknologi Sejahtera — ISP Fiber Optik Lampung Timur')">
    <meta property="og:image" content="@stack('og_image', asset('images/og/default-og.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="PT Jaringan Teknologi Sejahtera">
    <meta property="og:locale" content="id_ID">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@stack('twitter_title', config('app.name'))">
    <meta name="twitter:description" content="@stack('twitter_description', 'PT Jaringan Teknologi Sejahtera — ISP Fiber Optik Lampung Timur')">
    <meta name="twitter:image" content="@stack('twitter_image', asset('images/og/default-og.jpg'))">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "PT Jaringan Teknologi Sejahtera",
        "alternateName": "JTS",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('images/logo/jts-logo-full.png') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+6282183999981",
            "contactType": "customer service",
            "availableLanguage": "Indonesian"
        },
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Dusun 1 Suko Rini, Rt/Rw 002/001, Desa Rukti Sedyo, Kec. Raman Utara",
            "addressLocality": "Lampung Timur",
            "addressRegion": "Lampung",
            "postalCode": "34371",
            "addressCountry": "ID"
        },
        "sameAs": ["https://instagram.com/ptjts.id", "https://facebook.com/ptjts.id"]
    }
    </script>
    @stack('schema_markup')

    @if(config('services.analytics.search_console_verification'))
        <meta name="google-site-verification" content="{{ config('services.analytics.search_console_verification') }}">
    @endif

    @if(config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('images/logo/jts-favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/jts-favicon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/jts-apple-touch.png') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/scss/app.scss', 'resources/js/app.ts'])

    @stack('head_scripts')
</head>
<body class="bg-surface-light dark:bg-surface-dark text-ink-900 dark:text-ink-50 antialiased overflow-x-hidden transition-colors duration-300">

    <div id="jts-loading-screen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-ink-950 transition-opacity duration-700" aria-hidden="true">
        <div class="text-center">
            <div class="inline-flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-brand flex items-center justify-center">
                    <span class="text-white font-bold text-lg">J</span>
                </div>
                <span class="text-white font-bold text-2xl tracking-tight">JTS</span>
            </div>
            <div class="w-48 h-0.5 bg-ink-800 rounded-full mx-auto overflow-hidden">
                <div id="jts-loading-bar" class="h-full bg-brand rounded-full origin-left" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <div id="jts-cursor" class="fixed top-0 left-0 pointer-events-none z-50 hidden lg:block" aria-hidden="true">
        <div id="jts-cursor-dot" class="absolute -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-brand rounded-full transition-transform duration-100"></div>
        <div id="jts-cursor-ring" class="absolute -translate-x-1/2 -translate-y-1/2 w-9 h-9 border border-brand/50 rounded-full transition-all duration-300"></div>
    </div>

    <div id="jts-mouse-glow" class="fixed pointer-events-none inset-0 z-20 opacity-0 transition-opacity duration-500" aria-hidden="true">
        <div id="jts-glow-orb" class="absolute w-96 h-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand/10 blur-3xl pointer-events-none"></div>
    </div>

    @php
        $announcements = \App\Models\Announcement::query()->where('is_active', true)
            ->where(function ($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>', now()); })
            ->latest()->take(1)->get();
    @endphp
    @foreach($announcements as $announcement)
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="relative z-50 py-2 px-4 text-center text-sm font-medium
                {{ $announcement->severity === 'critical' ? 'bg-red-600 text-white' : ($announcement->severity === 'warning' ? 'bg-amber-500 text-ink-950' : 'bg-brand text-white') }}">
            <span>{{ $announcement->title }}</span>
            <button @click="show = false" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-70 hover:opacity-100" aria-label="Tutup pengumuman">✕</button>
        </div>
    @endforeach

    @include('partials.navigation')

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    @include('partials.footer')

    @php
        $popup = \App\Models\Popup::query()->where('is_active', true)
            ->where(function ($q) { $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()); })
            ->where(function ($q) { $q->whereNull('ends_at')->orWhere('ends_at', '>', now()); })
            ->first();
    @endphp
    @if($popup)
        @include('partials.popup', ['popup' => $popup])
    @endif

    <a href="https://wa.me/{{ config('services.whatsapp.admin_number') }}?text={{ urlencode('Halo JTS, saya ingin bertanya tentang layanan internet...') }}"
       target="_blank" rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-40 w-14 h-14 bg-[#25D366] hover:bg-[#1da851] text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 group"
       aria-label="Chat WhatsApp JTS">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="absolute right-16 bg-ink-900 dark:bg-ink-100 text-ink-50 dark:text-ink-900 text-xs px-3 py-1.5 rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none shadow-md">
            Chat WhatsApp
        </span>
    </a>

    <button id="jts-scroll-top"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="fixed bottom-24 right-6 z-40 w-10 h-10 bg-brand hover:bg-brand-600 text-white rounded-full shadow-md hover:shadow-glow flex items-center justify-center transition-all duration-300 opacity-0 translate-y-4 hover:scale-110"
            aria-label="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    @if(config('services.analytics.ga_id') && app()->environment('production'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics.ga_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.analytics.ga_id') }}', { anonymize_ip: true });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
