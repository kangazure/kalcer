{{--
    Popup promosi/informasi, ditampilkan sesuai aturan display_rule pada model
    Popup. Logika "sudah tampil atau belum" ditangani di sisi client via
    sessionStorage-like flag in-memory (bukan localStorage, sesuai batasan
    artifact) -- untuk halaman Blade biasa ini aman memakai cookie sederhana.
--}}
<div x-data="{
        show: false,
        init() {
            const rule = '{{ $popup->display_rule }}';
            const key = 'jts_popup_{{ $popup->id }}_shown';
            const already = document.cookie.split('; ').find(row => row.startsWith(key));

            if (rule === 'every_visit' || !already) {
                setTimeout(() => {
                    this.show = true;
                    const maxAge = rule === 'once_per_day' ? 86400 : (rule === 'once_per_session' ? '' : 86400 * 365);
                    document.cookie = `${key}=1; path=/;` + (maxAge ? ` max-age=${maxAge};` : '');
                }, {{ $popup->show_delay_ms }});
            }
        }
     }"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-expo-out duration-400"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     class="fixed inset-0 z-[200] flex items-center justify-center p-4"
     @keydown.escape.window="show = false"
     role="dialog"
     aria-modal="true"
     aria-labelledby="popup-title">

    <div class="absolute inset-0 bg-ink-950/70 backdrop-blur-sm" @click="show = false" aria-hidden="true"></div>

    <div class="relative bg-white dark:bg-ink-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
        <button @click="show = false"
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 dark:bg-ink-800/90 text-ink-600 dark:text-ink-300 flex items-center justify-center hover:bg-white transition-colors"
                aria-label="Tutup popup">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        @if($popup->image_url)
            <img src="{{ $popup->image_url }}" alt="{{ $popup->title }}" class="w-full h-48 object-cover">
        @endif

        <div class="p-6">
            <h2 id="popup-title" class="text-xl font-bold text-ink-900 dark:text-white mb-2">{{ $popup->title }}</h2>
            @if($popup->content)
                <p class="text-sm text-ink-600 dark:text-ink-400 leading-relaxed mb-5">{{ $popup->content }}</p>
            @endif
            @if($popup->link_url)
                <a href="{{ $popup->link_url }}" class="block w-full text-center py-3 bg-brand hover:bg-brand-600 text-white font-semibold rounded-xl transition-colors">
                    {{ $popup->link_label ?? 'Selengkapnya' }}
                </a>
            @endif
        </div>
    </div>
</div>
