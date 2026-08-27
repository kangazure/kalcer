@extends('layouts.app')
@push('seo_title')Testimoni Pelanggan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Apa kata pelanggan tentang layanan internet fiber optik PT Jaringan Teknologi Sejahtera (JTS).@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Testimoni</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Apa Kata Pelanggan Kami?</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Kepuasan pelanggan adalah prioritas utama kami. Berikut pengalaman nyata mereka menggunakan layanan JTS.
        </p>
    </div>
</section>

<section class="py-20 bg-surface-soft dark:bg-ink-900">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($testimonials as $index => $testimonial)
                <article class="glass-card dark:border-ink-700 rounded-2xl p-6 flex flex-col" data-aos="fade-up" data-aos-delay="{{ min($index * 60, 240) }}">
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
                            <cite class="not-italic font-semibold text-ink-900 dark:text-white text-sm block">{{ $testimonial->customer_name }}</cite>
                            @if($testimonial->customer_role)<span class="text-xs text-ink-400">{{ $testimonial->customer_role }}</span>@endif
                        </div>
                    </footer>
                </article>
            @empty
                <div class="col-span-full text-center py-16 text-ink-400">Belum ada testimoni yang dipublikasikan.</div>
            @endforelse
        </div>

        <div class="text-center mt-16">
            <h2 class="text-xl font-bold text-ink-900 dark:text-white mb-3" data-aos="fade-up">Sudah Menjadi Pelanggan JTS?</h2>
            <p class="text-ink-500 dark:text-ink-400 mb-6" data-aos="fade-up" data-aos-delay="100">Bagikan pengalaman Anda menggunakan layanan kami.</p>
            <a href="{{ route('contact.index') }}" class="btn-primary" data-aos="fade-up" data-aos-delay="200">Kirim Testimoni</a>
        </div>
    </div>
</section>
@endsection
