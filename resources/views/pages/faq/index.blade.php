@extends('layouts.app')
@push('seo_title')FAQ — Pertanyaan Umum PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Temukan jawaban atas pertanyaan umum seputar pendaftaran, teknis, tagihan, dan gangguan layanan internet JTS.@endpush
@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach($faqsByCategory->flatten() as $faq)
        {
            "@type": "Question",
            "name": @json($faq->question),
            "acceptedAnswer": { "@type": "Answer", "text": @json(strip_tags($faq->answer)) }
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">FAQ</span>
        <h1 class="page-hero-title" data-aos="fade-up">Pertanyaan yang Sering Diajukan</h1>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950" x-data="{ openIndex: null }">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        @forelse($faqsByCategory as $category => $faqs)
            <div data-aos="fade-up">
                <h2 class="text-lg font-bold text-brand uppercase tracking-wide mb-5">{{ $category }}</h2>
                <div class="space-y-3">
                    @foreach($faqs as $faq)
                        @php $uid = $category.'-'.$faq->id; @endphp
                        <div class="border border-ink-100 dark:border-ink-800 rounded-2xl overflow-hidden">
                            <button @click="openIndex = openIndex === '{{ $uid }}' ? null : '{{ $uid }}'"
                                    class="w-full flex items-center justify-between gap-4 p-5 text-left hover:bg-surface-soft dark:hover:bg-ink-900 transition-colors">
                                <span class="font-medium text-ink-800 dark:text-ink-200 text-sm">{{ $faq->question }}</span>
                                <svg class="w-5 h-5 text-ink-400 shrink-0 transition-transform" :class="openIndex === '{{ $uid }}' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="openIndex === '{{ $uid }}'" x-collapse>
                                <div class="px-5 pb-5 text-sm text-ink-600 dark:text-ink-400 leading-relaxed prose-jts">
                                    {!! \Illuminate\Support\Str::markdown($faq->answer) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-center text-ink-400 py-16">Belum ada FAQ tersedia.</p>
        @endforelse

        <div class="text-center pt-10 border-t border-ink-100 dark:border-ink-800">
            <h2 class="text-lg font-bold text-ink-900 dark:text-white mb-3">Tidak menemukan jawaban yang Anda cari?</h2>
            <a href="{{ route('contact.index') }}" class="btn-primary">Hubungi Tim Kami</a>
        </div>
    </div>
</section>
@endsection
