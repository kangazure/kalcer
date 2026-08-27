<header
    x-data="{
        open: false,
        scrolled: false,
        activeMenu: null,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 60;
            });
        }
    }"
    @keydown.escape="open = false; activeMenu = null"
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500"
    :class="scrolled ? 'py-0 backdrop-blur-xl bg-white/90 dark:bg-ink-950/90 shadow-glass border-b border-ink-100/50 dark:border-ink-800/50' : 'py-2 bg-transparent'"
>
    <nav class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group" aria-label="PT Jaringan Teknologi Sejahtera">
            <img src="{{ asset('images/logo/jts-logo-mark-square.png') }}"
                 alt="Logo JTS"
                 class="h-10 w-10 object-contain group-hover:scale-105 transition-transform duration-300">
            <div class="hidden sm:block">
                <div class="font-bold text-ink-900 dark:text-white text-base leading-tight tracking-tight">JTS</div>
                <div class="text-[10px] text-ink-500 dark:text-ink-400 leading-tight">Jaringan Teknologi</div>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden lg:flex items-center gap-1">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link--active' : '' }}">Beranda</a>

            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="nav-link flex items-center gap-1 {{ request()->routeIs('about.*') ? 'nav-link--active' : '' }}" :class="open && 'nav-link--active'" aria-expanded="open" aria-haspopup="true">
                    Tentang
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-expo-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 mt-2 w-52 glass-card rounded-xl p-2 shadow-glass" role="menu">
                    <a href="{{ route('about.index') }}" class="dropdown-item" role="menuitem">Profil Perusahaan</a>
                    <a href="{{ route('about.vision') }}" class="dropdown-item" role="menuitem">Visi &amp; Misi</a>
                    <a href="{{ route('about.history') }}" class="dropdown-item" role="menuitem">Sejarah Perusahaan</a>
                </div>
            </div>

            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="nav-link flex items-center gap-1 {{ request()->routeIs('services.*') ? 'nav-link--active' : '' }}" :class="open && 'nav-link--active'" aria-expanded="open" aria-haspopup="true">
                    Layanan
                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition:enter="transition ease-expo-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0 translate-y-1"
                     class="absolute top-full left-0 mt-2 w-60 glass-card rounded-xl p-2 shadow-glass" role="menu">
                    @php $navServices = \App\Models\Service::active()->orderBy('sort_order')->get(); @endphp
                    @foreach($navServices as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="dropdown-item" role="menuitem">{{ $service->name }}</a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('packages.index') }}" class="nav-link {{ request()->routeIs('packages.*') ? 'nav-link--active' : '' }}">Paket</a>
            <a href="{{ route('coverage.index') }}" class="nav-link {{ request()->routeIs('coverage.*') ? 'nav-link--active' : '' }}">Coverage</a>
            <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'nav-link--active' : '' }}">Blog</a>
            <a href="{{ route('portfolio.index') }}" class="nav-link {{ request()->routeIs('portfolio.*') ? 'nav-link--active' : '' }}">Portfolio</a>
            <a href="{{ route('career.index') }}" class="nav-link {{ request()->routeIs('career.*') ? 'nav-link--active' : '' }}">Karir</a>
            <a href="{{ route('contact.index') }}" class="nav-link {{ request()->routeIs('contact.*') ? 'nav-link--active' : '' }}">Kontak</a>
        </div>

        {{-- CTA + Actions --}}
        <div class="flex items-center gap-3">
            <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-ink-600 dark:text-ink-400 hover:bg-ink-100 dark:hover:bg-ink-800 transition-colors" aria-label="Toggle dark mode">
                <svg x-show="!darkMode" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <a href="{{ route('coverage.index') }}" class="hidden lg:inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-all duration-300 hover:shadow-glow hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Cek Jangkauan
            </a>

            <button @click="open = !open" class="lg:hidden p-2 rounded-lg text-ink-600 dark:text-ink-400 hover:bg-ink-100 dark:hover:bg-ink-800 transition-colors" :aria-expanded="open" aria-controls="mobile-menu" aria-label="Menu navigasi">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" x-show="open" x-cloak x-transition:enter="transition ease-expo-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden border-t border-ink-100 dark:border-ink-800 bg-white/95 dark:bg-ink-950/95 backdrop-blur-xl max-h-[80vh] overflow-y-auto">
        <div class="px-4 py-4 space-y-1">
            <a href="{{ route('home') }}" class="mobile-nav-link" @click="open = false">Beranda</a>
            <a href="{{ route('about.index') }}" class="mobile-nav-link" @click="open = false">Tentang Kami</a>
            <a href="{{ route('about.vision') }}" class="mobile-nav-link pl-8 text-sm" @click="open = false">Visi &amp; Misi</a>
            <a href="{{ route('services.index') }}" class="mobile-nav-link" @click="open = false">Layanan</a>
            <a href="{{ route('packages.index') }}" class="mobile-nav-link" @click="open = false">Paket Internet</a>
            <a href="{{ route('coverage.index') }}" class="mobile-nav-link" @click="open = false">Coverage Area</a>
            <a href="{{ route('blog.index') }}" class="mobile-nav-link" @click="open = false">Blog</a>
            <a href="{{ route('portfolio.index') }}" class="mobile-nav-link" @click="open = false">Portfolio</a>
            <a href="{{ route('testimonial.index') }}" class="mobile-nav-link" @click="open = false">Testimoni</a>
            <a href="{{ route('career.index') }}" class="mobile-nav-link" @click="open = false">Karir</a>
            <a href="{{ route('faq.index') }}" class="mobile-nav-link" @click="open = false">FAQ</a>
            <a href="{{ route('contact.index') }}" class="mobile-nav-link" @click="open = false">Kontak</a>
            <div class="pt-3 pb-1">
                <a href="{{ route('coverage.index') }}" class="block w-full text-center py-3 bg-brand text-white font-semibold rounded-xl" @click="open = false">Cek Jangkauan Sekarang</a>
            </div>
        </div>
    </div>
</header>
