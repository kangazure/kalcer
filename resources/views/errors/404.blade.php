@extends('layouts.app')

@push('seo_title')Halaman Tidak Ditemukan (404) — PT Jaringan Teknologi Sejahtera@endpush
@push('robots')noindex, nofollow@endpush

@section('content')
<section class="min-h-screen flex items-center justify-center bg-ink-950 relative overflow-hidden px-4">
    <div class="absolute inset-0 bg-grid-pattern opacity-[0.03]" aria-hidden="true" style="background-size:40px 40px"></div>
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-96 h-96 rounded-full bg-brand/10 blur-[120px] pointer-events-none" aria-hidden="true"></div>

    <div class="relative text-center max-w-lg mx-auto" data-aos="fade-up">
        <img src="{{ asset('images/logo/jts-logo-mark-square.png') }}" alt="Logo JTS" class="h-16 w-16 object-contain mx-auto mb-8 opacity-80">

        <h1 class="text-8xl sm:text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-brand to-orange-400 mb-4">404</h1>
        <h2 class="text-2xl font-bold text-white mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-ink-400 leading-relaxed mb-10">
            Sepertinya sinyal koneksi ke halaman ini terputus. Halaman yang Anda cari mungkin sudah dipindahkan atau tidak pernah ada.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand hover:bg-brand-600 text-white font-semibold rounded-xl transition-all hover:shadow-glow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-6 py-3 glass-card text-white font-semibold rounded-xl hover:bg-white/10 transition-all">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection
