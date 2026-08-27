@extends('layouts.app')
@push('seo_title')Kontak Kami — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Hubungi PT Jaringan Teknologi Sejahtera untuk pendaftaran, konsultasi, atau pertanyaan seputar layanan internet fiber optik.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Kontak</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Hubungi Kami</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Tim kami siap membantu menjawab pertanyaan Anda seputar pendaftaran, layanan, dan dukungan teknis.
        </p>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-10">

            <div class="lg:col-span-2 space-y-4" data-aos="fade-right">
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 flex gap-4 items-start">
                    <div class="w-11 h-11 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-1">Kantor Pusat</h3>
                        <p class="text-sm text-ink-500 dark:text-ink-400">Dusun 1 Suko Rini, Rt/Rw 002/001, Desa Rukti Sedyo, Kec. Raman Utara, Kab. Lampung Timur 34371</p>
                    </div>
                </div>
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 flex gap-4 items-start">
                    <div class="w-11 h-11 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-1">Telepon &amp; WhatsApp</h3>
                        <a href="tel:+6282183999981" class="text-sm text-ink-500 dark:text-ink-400 hover:text-brand block">+62 821-8399-9981</a>
                    </div>
                </div>
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 flex gap-4 items-start">
                    <div class="w-11 h-11 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-1">Email</h3>
                        <a href="mailto:info@ptjts.id" class="text-sm text-ink-500 dark:text-ink-400 hover:text-brand block">info@ptjts.id</a>
                    </div>
                </div>
                <div class="glass-card dark:border-ink-700 rounded-2xl p-6 flex gap-4 items-start">
                    <div class="w-11 h-11 rounded-xl bg-brand/10 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-ink-900 dark:text-white text-sm mb-1">Jam Operasional</h3>
                        <p class="text-sm text-ink-500 dark:text-ink-400">Senin - Sabtu, 08:00 - 17:00 WIB<br>Dukungan Teknis: 24/7</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3" data-aos="fade-left">
                <div class="glass-card dark:border-ink-700 rounded-2xl p-8"
                     x-data="{
                        submitting: false, done: false, error: null,
                        submitForm(e) {
                            this.submitting = true; this.error = null;
                            const form = new FormData(e.target);
                            const payload = Object.fromEntries(form.entries());
                            fetch('{{ route('contact.submit') }}', {
                                method: 'POST',
                                headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                                body: JSON.stringify(payload)
                            })
                            .then(r => r.json().then(data => ({status: r.status, data})))
                            .then(({status, data}) => {
                                if (status >= 400) { this.error = data.message || 'Gagal mengirim pesan.'; this.submitting = false; return; }
                                this.done = true; this.submitting = false;
                            })
                            .catch(() => { this.error = 'Terjadi kesalahan. Silakan coba lagi.'; this.submitting = false; });
                        }
                     ">
                    <h2 class="font-bold text-ink-900 dark:text-white text-lg mb-6">Kirim Pesan</h2>

                    <div x-show="!done">
                        <form @submit.prevent="submitForm($event)" class="space-y-4">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <input type="text" name="name" placeholder="Nama Lengkap" required class="form-input text-sm">
                                <input type="email" name="email" placeholder="Email" required class="form-input text-sm">
                            </div>
                            <input type="text" name="phone" placeholder="Nomor HP/WhatsApp" class="form-input text-sm">
                            <input type="text" name="subject" value="{{ request('paket') ? 'Pendaftaran Paket: '.request('paket') : (request('layanan') ? 'Konsultasi Layanan: '.request('layanan') : '') }}" placeholder="Subjek" class="form-input text-sm">
                            <textarea name="message" rows="5" placeholder="Tulis pesan Anda..." required minlength="10" class="form-textarea text-sm"></textarea>

                            <div x-show="error" x-cloak class="alert-error text-xs"><span x-text="error"></span></div>

                            <button type="submit" :disabled="submitting" class="w-full btn-primary justify-center">
                                <span x-text="submitting ? 'Mengirim...' : 'Kirim Pesan'"></span>
                            </button>
                        </form>
                    </div>
                    <div x-show="done" x-cloak class="text-center py-10">
                        <svg class="w-14 h-14 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="font-bold text-ink-900 dark:text-white mb-2">Pesan Terkirim!</h3>
                        <p class="text-sm text-ink-500 dark:text-ink-400">Terima kasih, tim kami akan segera menghubungi Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-16 rounded-2xl overflow-hidden h-72 bg-ink-100 dark:bg-ink-800 flex items-center justify-center" data-aos="fade-up">
            <div class="text-center text-ink-400 text-sm">
                <svg class="w-10 h-10 mb-2 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Peta lokasi kantor — aktifkan Google Maps API
            </div>
        </div>
    </div>
</section>
@endsection
