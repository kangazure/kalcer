@extends('layouts.app')
@push('seo_title')Sejarah Perusahaan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Perjalanan PT Jaringan Teknologi Sejahtera sejak didirikan hingga menjadi penyedia internet fiber optik terpercaya di Lampung.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Sejarah</span>
        <h1 class="page-hero-title" data-aos="fade-up">Perjalanan PT Jaringan Teknologi Sejahtera</h1>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative border-l-2 border-brand/30 pl-8 space-y-12">
            @foreach([
                ['date' => '6 Juni 2024', 'title' => 'Pendirian Perusahaan', 'desc' => 'PT Jaringan Teknologi Sejahtera resmi didirikan berdasarkan Akta Notaris No. 836 dari Santy Sagita, S.H., M.Kn., Notaris di Kota Cilegon.'],
                ['date' => '2024', 'title' => 'Pengesahan Badan Hukum', 'desc' => 'Perusahaan disahkan oleh Menteri Hukum RI dengan Nomor AHU-0111314.AH.01.11.TAHUN 2024, menandai dimulainya operasional resmi JTS.'],
                ['date' => '2024', 'title' => 'Pembangunan POP Pertama', 'desc' => 'JTS membangun POP01 di Raman Utara sebagai server utama, menjadi titik awal ekspansi jaringan fiber optik ke wilayah sekitar.'],
                ['date' => '2024 - Sekarang', 'title' => 'Ekspansi Jaringan', 'desc' => 'Perluasan jaringan fiber optik ke Way Bungur, Purbolinggo, Seputih Banyak, dan Kota Gajah, menjangkau pelanggan rumah dan bisnis di Lampung Timur dan Lampung Tengah.'],
            ] as $index => $milestone)
                <div class="relative" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="absolute -left-[42px] w-5 h-5 rounded-full bg-brand ring-4 ring-white dark:ring-ink-950"></div>
                    <span class="inline-block text-xs font-semibold text-brand uppercase tracking-wider mb-2">{{ $milestone['date'] }}</span>
                    <h3 class="text-lg font-bold text-ink-900 dark:text-white mb-2">{{ $milestone['title'] }}</h3>
                    <p class="text-sm text-ink-600 dark:text-ink-400 leading-relaxed">{{ $milestone['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
