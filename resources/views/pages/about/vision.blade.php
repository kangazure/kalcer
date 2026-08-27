@extends('layouts.app')
@push('seo_title')Visi & Misi — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Visi dan misi PT Jaringan Teknologi Sejahtera dalam menghadirkan layanan internet fiber optik berkualitas untuk masyarakat Lampung.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Visi &amp; Misi</span>
        <h1 class="page-hero-title" data-aos="fade-up">Komitmen Kami untuk Masa Depan Digital</h1>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <div class="text-center" data-aos="fade-up">
            <div class="w-16 h-16 rounded-2xl bg-brand/10 flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <span class="section-label">Visi</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-ink-900 dark:text-white max-w-2xl mx-auto leading-snug">
                Menjadi penyedia jasa internet terdepan dan terpercaya yang menghadirkan konektivitas digital merata bagi masyarakat dan pelaku usaha di Provinsi Lampung.
            </h2>
        </div>

        <div data-aos="fade-up">
            <div class="text-center mb-10">
                <div class="w-16 h-16 rounded-2xl bg-brand/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="section-label">Misi</span>
                <h2 class="text-2xl font-bold text-ink-900 dark:text-white">Langkah Kami Mewujudkan Visi</h2>
            </div>
            <div class="grid sm:grid-cols-2 gap-6">
                @foreach([
                    'Membangun dan mengembangkan infrastruktur jaringan fiber optik yang andal, cepat, dan berkelanjutan.',
                    'Memberikan layanan internet berkualitas tinggi dengan harga yang kompetitif dan transparan.',
                    'Meningkatkan kualitas sumber daya manusia yang profesional dan responsif terhadap kebutuhan pelanggan.',
                    'Memperluas jangkauan layanan ke wilayah-wilayah yang belum terjangkau infrastruktur digital.',
                    'Mendukung transformasi digital UMKM dan masyarakat melalui akses internet yang terjangkau.',
                    'Menjaga keandalan dan keamanan jaringan melalui monitoring dan pemeliharaan berkelanjutan.',
                ] as $index => $mission)
                    <div class="flex gap-4 p-5 bg-surface-soft dark:bg-ink-900 rounded-2xl" data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                        <div class="w-8 h-8 rounded-lg bg-brand text-white flex items-center justify-center shrink-0 font-bold text-sm">{{ $index + 1 }}</div>
                        <p class="text-sm text-ink-600 dark:text-ink-400 leading-relaxed">{{ $mission }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
