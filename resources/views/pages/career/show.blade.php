@extends('layouts.app')
@push('seo_title'){{ $career->title }} — Karir JTS@endpush
@push('seo_description'){{ Str::limit(strip_tags($career->description), 160) }}@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-ink-500 mb-6" aria-label="Breadcrumb" data-aos="fade-down">
            <a href="{{ route('career.index') }}" class="hover:text-brand">Karir</a>
            <span class="mx-2" aria-hidden="true">/</span>
            <span class="text-ink-300">{{ $career->title }}</span>
        </nav>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">{{ $career->title }}</h1>
        <div class="flex flex-wrap items-center gap-4 text-sm text-ink-400" data-aos="fade-up" data-aos-delay="100">
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>{{ $career->location }}</span>
            <span class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>{{ str_replace('_',' ',ucfirst($career->job_type)) }}</span>
            @if($career->vacancy_count > 1)<span class="badge-brand text-xs">{{ $career->vacancy_count }} Posisi</span>@endif
        </div>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div data-aos="fade-up">
                    <h2 class="font-bold text-ink-900 dark:text-white mb-3">Deskripsi Pekerjaan</h2>
                    <div class="prose-jts">{!! \Illuminate\Support\Str::markdown($career->description) !!}</div>
                </div>

                @if(!empty($career->responsibilities))
                <div data-aos="fade-up">
                    <h2 class="font-bold text-ink-900 dark:text-white mb-3">Tanggung Jawab</h2>
                    <ul class="space-y-2">
                        @foreach($career->responsibilities as $item)
                            <li class="flex gap-2.5 items-start text-sm text-ink-600 dark:text-ink-400"><svg class="w-4 h-4 text-brand shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($career->requirements))
                <div data-aos="fade-up">
                    <h2 class="font-bold text-ink-900 dark:text-white mb-3">Kualifikasi</h2>
                    <ul class="space-y-2">
                        @foreach($career->requirements as $item)
                            <li class="flex gap-2.5 items-start text-sm text-ink-600 dark:text-ink-400"><svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($career->benefits))
                <div data-aos="fade-up">
                    <h2 class="font-bold text-ink-900 dark:text-white mb-3">Benefit</h2>
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach($career->benefits as $item)
                            <div class="flex gap-2.5 items-center p-3 bg-surface-soft dark:bg-ink-900 rounded-xl text-sm text-ink-600 dark:text-ink-400">
                                <svg class="w-4 h-4 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <aside>
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 sticky top-24"
                     x-data="{ submitting: false, done: false, error: null,
                        submitForm(e) {
                            this.submitting = true; this.error = null;
                            const formData = new FormData(e.target);
                            fetch('{{ route('career.apply', $career->id) }}', { method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}, body: formData })
                                .then(r => r.json().then(data => ({ status: r.status, data })))
                                .then(({status, data}) => {
                                    if (status >= 400) { this.error = data.message || 'Gagal mengirim lamaran.'; this.submitting = false; return; }
                                    this.done = true; this.submitting = false;
                                })
                                .catch(() => { this.error = 'Terjadi kesalahan. Silakan coba lagi.'; this.submitting = false; });
                        }
                     ">
                    <h3 class="font-bold text-ink-900 dark:text-white mb-4">Lamar Posisi Ini</h3>

                    <div x-show="!done">
                        <form @submit.prevent="submitForm($event)" enctype="multipart/form-data" class="space-y-3">
                            <input type="text" name="full_name" placeholder="Nama Lengkap" required class="form-input text-sm">
                            <input type="email" name="email" placeholder="Email" required class="form-input text-sm">
                            <input type="text" name="phone" placeholder="Nomor HP/WhatsApp" required class="form-input text-sm">
                            <div>
                                <label class="form-label text-xs">Upload CV (PDF/DOC, max 5MB)</label>
                                <input type="file" name="resume" accept=".pdf,.doc,.docx" required class="form-input text-sm">
                            </div>
                            <textarea name="cover_letter" rows="3" placeholder="Ceritakan mengapa Anda cocok untuk posisi ini (opsional)" class="form-textarea text-sm"></textarea>

                            <div x-show="error" x-cloak class="alert-error text-xs"><span x-text="error"></span></div>

                            <button type="submit" :disabled="submitting" class="w-full btn-primary btn-sm justify-center text-sm">
                                <span x-text="submitting ? 'Mengirim...' : 'Kirim Lamaran'"></span>
                            </button>
                        </form>
                    </div>
                    <div x-show="done" x-cloak class="alert-success">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <div>
                            <p class="font-semibold">Lamaran Terkirim!</p>
                            <p class="text-sm mt-1">Tim kami akan menghubungi Anda jika lolos seleksi awal.</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
