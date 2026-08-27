@extends('layouts.app')
@push('seo_title')Cek Jangkauan Internet — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Cek apakah lokasi Anda sudah terjangkau layanan internet fiber optik JTS. Melayani Raman Utara, Way Bungur, Purbolinggo, Seputih Banyak, dan Kota Gajah.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Coverage Area</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Cek Jangkauan Internet di Lokasi Anda</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Masukkan alamat atau gunakan lokasi GPS Anda untuk mengetahui apakah JTS sudah menjangkau wilayah Anda.
        </p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950"
         x-data="{
            loading: false, result: null, error: null, address: '', lat: null, lng: null,
            useMyLocation() {
                if (!navigator.geolocation) { this.error = 'Browser Anda tidak mendukung geolokasi.'; return; }
                this.loading = true;
                navigator.geolocation.getCurrentPosition(
                    (pos) => { this.lat = pos.coords.latitude; this.lng = pos.coords.longitude; this.checkCoverage(); },
                    () => { this.loading = false; this.error = 'Gagal mendapatkan lokasi. Izinkan akses lokasi di browser Anda.'; }
                );
            },
            checkCoverage() {
                this.loading = true; this.error = null; this.result = null;
                fetch('{{ route('coverage.check') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ latitude: this.lat, longitude: this.lng, address: this.address })
                })
                .then(r => r.json())
                .then(data => { this.result = data; this.loading = false; })
                .catch(() => { this.error = 'Terjadi kesalahan, silakan coba lagi.'; this.loading = false; });
            }
         }">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 items-start">

            {{-- Form Cek --}}
            <div class="glass-card dark:border-ink-700 rounded-2xl p-8" data-aos="fade-right">
                <h2 class="font-bold text-ink-900 dark:text-white text-lg mb-6">Cek Jangkauan Sekarang</h2>

                <button @click="useMyLocation()" :disabled="loading"
                        class="w-full flex items-center justify-center gap-2 py-3 mb-4 border-2 border-brand text-brand hover:bg-brand hover:text-white font-semibold rounded-xl transition-all disabled:opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Gunakan Lokasi Saya
                </button>

                <div class="relative text-center my-4">
                    <span class="text-xs text-ink-400 bg-white dark:bg-ink-950 px-3 relative z-10">atau masukkan alamat manual</span>
                    <div class="absolute top-1/2 left-0 right-0 h-px bg-ink-100 dark:bg-ink-800"></div>
                </div>

                <label for="address-input" class="form-label">Alamat Lengkap</label>
                <textarea id="address-input" x-model="address" rows="3" placeholder="Contoh: Dusun 1, Desa Rukti Sedyo, Kec. Raman Utara" class="form-textarea text-sm mb-4"></textarea>

                <p class="text-xs text-ink-400 mb-4">
                    Untuk hasil paling akurat, gunakan tombol "Gunakan Lokasi Saya" karena sistem menghitung jarak berdasarkan koordinat GPS ke titik POP terdekat.
                </p>

                <div x-show="error" x-cloak class="alert-error mb-4">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span x-text="error"></span>
                </div>

                <div x-show="loading" x-cloak class="text-center py-6">
                    <svg class="animate-spin w-8 h-8 text-brand mx-auto" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="text-sm text-ink-400 mt-2">Mengecek jangkauan...</p>
                </div>

                <div x-show="result" x-cloak x-transition>
                    <template x-if="result && result.is_covered">
                        <div class="alert-success">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <div>
                                <p class="font-semibold">Lokasi Anda terjangkau!</p>
                                <p class="text-sm mt-1" x-show="result.nearest_area">Area terdekat: <span x-text="result.nearest_area?.region_name"></span> (<span x-text="Math.round(result.nearest_area?.distance_meters / 1000 * 10) / 10"></span> km)</p>
                                <a href="{{ route('contact.index') }}" class="inline-block mt-3 text-sm font-semibold underline">Daftar sekarang →</a>
                            </div>
                        </div>
                    </template>
                    <template x-if="result && !result.is_covered">
                        <div class="alert-warning">
                            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-semibold">Lokasi Anda belum terjangkau saat ini</p>
                                <p class="text-sm mt-1">Tenang, kami sudah mencatat permintaan Anda. Tim kami akan mempertimbangkan ekspansi ke area Anda.</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Daftar Wilayah --}}
            <div data-aos="fade-left">
                <h2 class="font-bold text-ink-900 dark:text-white text-lg mb-6">Wilayah Terjangkau Saat Ini</h2>
                <div class="space-y-3">
                    @foreach($areas as $area)
                        <div class="flex items-center justify-between py-3 px-4 bg-surface-soft dark:bg-ink-900 rounded-xl border border-ink-100 dark:border-ink-800">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full {{ $area->coverage_status === 'available' ? 'bg-green-500' : ($area->coverage_status === 'partial' ? 'bg-amber-500' : 'bg-blue-500') }}" aria-hidden="true"></span>
                                <div>
                                    <span class="text-ink-700 dark:text-ink-300 text-sm font-medium block">{{ $area->region_name }}</span>
                                    <span class="text-xs text-ink-400">{{ $area->regency }}</span>
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                {{ $area->coverage_status === 'available' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' :
                                   ($area->coverage_status === 'partial' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' :
                                   'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400') }}">
                                {{ $area->coverage_status === 'available' ? 'Tersedia' : ($area->coverage_status === 'partial' ? 'Sebagian' : 'Segera Hadir') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 rounded-2xl overflow-hidden h-64 bg-ink-100 dark:bg-ink-800 flex items-center justify-center">
                    <div class="text-center text-ink-400 text-sm">
                        <svg class="w-10 h-10 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Peta interaktif — aktifkan Google Maps API
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
