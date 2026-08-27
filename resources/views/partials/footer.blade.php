@php
    use App\Models\Service;
    use App\Services\SettingService;
    $footerServices = Service::active()->orderBy('sort_order')->get();
    $settings = app(SettingService::class)->getPublicSettings();
    $general = $settings['general'] ?? [];
@endphp

<footer class="bg-ink-950 text-ink-300 relative overflow-hidden" aria-label="Footer">
    <div class="absolute inset-0 bg-grid-pattern opacity-5 pointer-events-none" aria-hidden="true"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand/50 to-transparent" aria-hidden="true"></div>

    <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Column 1: Brand & Info --}}
            <div class="lg:col-span-1 space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" aria-label="JTS Home">
                    <img src="{{ asset('images/logo/jts-logo-mark-square.png') }}"
                         alt="Logo PT Jaringan Teknologi Sejahtera"
                         class="h-12 w-12 object-contain group-hover:scale-105 transition-transform">
                    <div>
                        <div class="font-bold text-white text-lg leading-tight">JTS</div>
                        <div class="text-xs text-ink-500 leading-tight">Internet Service Provider</div>
                    </div>
                </a>

                <p class="text-sm leading-relaxed text-ink-400">
                    PT Jaringan Teknologi Sejahtera — Penyedia layanan internet fiber optik yang andal, cepat, dan terjangkau untuk rumah dan bisnis di Kabupaten Lampung Timur.
                </p>

                <div class="space-y-2 text-sm">
                    <div class="flex gap-3 items-start">
                        <svg class="w-4 h-4 text-brand mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-ink-400">Desa Rukti Sedyo, Kec. Raman Utara, Kab. Lampung Timur 34371</span>
                    </div>
                    <div class="flex gap-3 items-center">
                        <svg class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:+6282183999981" class="text-ink-400 hover:text-brand transition-colors">+62 821-8399-9981</a>
                    </div>
                    <div class="flex gap-3 items-center">
                        <svg class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:info@ptjts.id" class="text-ink-400 hover:text-brand transition-colors">info@ptjts.id</a>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    @if(!empty($general['social_instagram']))
                        <a href="{{ json_decode($general['social_instagram']) }}" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="Instagram JTS">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    @endif
                    @if(!empty($general['social_facebook']))
                        <a href="{{ json_decode($general['social_facebook']) }}" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="Facebook JTS">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                    <a href="https://wa.me/{{ config('services.whatsapp.admin_number') }}" target="_blank" rel="noopener noreferrer" class="social-btn" aria-label="WhatsApp JTS">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Column 2: Layanan --}}
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Layanan</h3>
                <ul class="space-y-2.5">
                    @foreach($footerServices as $svc)
                        <li>
                            <a href="{{ route('services.show', $svc->slug) }}"
                               class="text-sm text-ink-400 hover:text-brand transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand transition-colors" aria-hidden="true"></span>
                                {{ $svc->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Column 3: Perusahaan --}}
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Perusahaan</h3>
                <ul class="space-y-2.5">
                    @foreach([
                        ['href' => route('about.index'), 'label' => 'Tentang Kami'],
                        ['href' => route('about.vision'), 'label' => 'Visi & Misi'],
                        ['href' => route('about.history'), 'label' => 'Sejarah'],
                        ['href' => route('portfolio.index'), 'label' => 'Portfolio'],
                        ['href' => route('gallery.index'), 'label' => 'Galeri'],
                        ['href' => route('testimonial.index'), 'label' => 'Testimoni'],
                        ['href' => route('career.index'), 'label' => 'Karir'],
                        ['href' => route('blog.index'), 'label' => 'Blog'],
                    ] as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="text-sm text-ink-400 hover:text-brand transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand transition-colors" aria-hidden="true"></span>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Column 4: Newsletter & Support --}}
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Bantuan & Info</h3>
                <ul class="space-y-2.5 mb-8">
                    @foreach([
                        ['href' => route('faq.index'), 'label' => 'FAQ'],
                        ['href' => route('contact.index'), 'label' => 'Kontak Kami'],
                        ['href' => route('coverage.index'), 'label' => 'Cek Jangkauan'],
                        ['href' => route('network-status.index'), 'label' => 'Status Jaringan'],
                        ['href' => route('packages.index'), 'label' => 'Daftar Paket'],
                        ['href' => route('static.privacy'), 'label' => 'Kebijakan Privasi'],
                        ['href' => route('static.terms'), 'label' => 'Syarat & Ketentuan'],
                    ] as $link)
                        <li>
                            <a href="{{ $link['href'] }}"
                               class="text-sm text-ink-400 hover:text-brand transition-colors duration-200 flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-brand/50 group-hover:bg-brand transition-colors" aria-hidden="true"></span>
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div>
                    <p class="text-sm font-medium text-ink-300 mb-3">Newsletter — Tips Internet & Promo</p>
                    <form x-data="{ email: '', loading: false, done: false }"
                          @submit.prevent="
                            if(!email) return;
                            loading = true;
                            fetch('{{ route('newsletter.subscribe') }}', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                                body: JSON.stringify({email})
                            }).then(r => r.json()).then(() => { done = true; loading = false; }).catch(() => loading = false);
                          "
                          class="flex gap-2">
                        <label for="footer-newsletter-email" class="sr-only">Email newsletter</label>
                        <input id="footer-newsletter-email"
                               x-model="email"
                               type="email"
                               required
                               placeholder="email@anda.com"
                               class="flex-1 min-w-0 px-3 py-2 text-sm bg-ink-900 border border-ink-700 text-white placeholder-ink-500 rounded-lg focus:outline-none focus:border-brand transition-colors">
                        <button type="submit"
                                :disabled="loading || done"
                                class="px-4 py-2 bg-brand hover:bg-brand-600 disabled:opacity-60 text-white text-sm font-semibold rounded-lg transition-all whitespace-nowrap">
                            <span x-show="!loading && !done">Daftar</span>
                            <span x-show="loading" x-cloak>...</span>
                            <span x-show="done" x-cloak>✓</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="border-t border-ink-800/60 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-ink-500">
            <p>© {{ date('Y') }} PT Jaringan Teknologi Sejahtera. Semua hak dilindungi undang-undang.</p>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse" aria-hidden="true"></span>
                    Sistem Online
                </span>
                <a href="{{ route('blog.rss') }}" class="hover:text-brand transition-colors" aria-label="RSS Feed">RSS</a>
                <a href="{{ route('admin.login') }}" class="hover:text-brand transition-colors">Admin</a>
            </div>
        </div>
    </div>
</footer>
