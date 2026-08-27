@extends('layouts.app')
@push('seo_title')Status Jaringan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Pantau status jaringan real-time dan jadwal maintenance PT Jaringan Teknologi Sejahtera.@endpush

@section('content')
<section class="page-hero py-16">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Status Jaringan</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">Status Jaringan Real-time</h1>
        @php $allOnline = $nodes->every(fn($n) => $n->status === 'online'); @endphp
        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-medium {{ $allOnline ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}" data-aos="fade-up" data-aos-delay="100">
            <span class="w-2.5 h-2.5 rounded-full {{ $allOnline ? 'bg-green-400 animate-pulse' : 'bg-amber-400 animate-pulse' }}" aria-hidden="true"></span>
            {{ $allOnline ? 'Semua Sistem Beroperasi Normal' : 'Sebagian Sistem Mengalami Gangguan' }}
        </div>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950" id="status-container">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">

        @if($maintenances->isNotEmpty())
        <div class="mb-10" data-aos="fade-up">
            <h2 class="font-bold text-ink-900 dark:text-white mb-4">Jadwal Maintenance</h2>
            <div class="space-y-3">
                @foreach($maintenances as $m)
                    <div class="alert-info">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        <div>
                            <p class="font-semibold">{{ $m->title }} <span class="badge text-[10px] {{ $m->status === 'ongoing' ? 'badge-amber' : 'badge-blue' }}">{{ ucfirst($m->status) }}</span></p>
                            <p class="text-sm mt-1">{{ $m->description }}</p>
                            <p class="text-xs mt-1 opacity-75">{{ $m->scheduled_start->format('d M Y H:i') }} — {{ $m->scheduled_end->format('d M Y H:i') }} WIB</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <h2 class="font-bold text-ink-900 dark:text-white mb-5" data-aos="fade-up">Status per Titik POP</h2>
        <div class="space-y-3 mb-14">
            @foreach($nodes as $index => $node)
                <div class="flex items-center justify-between p-5 glass-card dark:border-ink-700 rounded-2xl" data-aos="fade-up" data-aos-delay="{{ min($index * 50, 200) }}">
                    <div class="flex items-center gap-4">
                        <span class="status-indicator {{ $node->status }} w-3 h-3" aria-hidden="true"></span>
                        <div>
                            <h3 class="font-semibold text-ink-900 dark:text-white text-sm">{{ $node->node_name }}</h3>
                            <p class="text-xs text-ink-400">Uptime: {{ $node->uptime_percent ? number_format($node->uptime_percent, 1).'%' : 'N/A' }}</p>
                        </div>
                    </div>
                    <span class="badge text-xs {{ $node->status === 'online' ? 'badge-green' : ($node->status === 'degraded' ? 'badge-amber' : 'badge-red') }}">
                        {{ ucfirst($node->status) }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="glass-card dark:border-ink-700 rounded-2xl p-8" data-aos="fade-up"
             x-data="{ submitting: false, done: false, error: null,
                submitForm(e) {
                    this.submitting = true; this.error = null;
                    const form = new FormData(e.target);
                    const payload = Object.fromEntries(form.entries());
                    fetch('{{ route('network-status.report') }}', {
                        method: 'POST',
                        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                        body: JSON.stringify(payload)
                    })
                    .then(r => r.json().then(data => ({status: r.status, data})))
                    .then(({status, data}) => {
                        if (status >= 400) { this.error = data.message || 'Gagal mengirim laporan.'; this.submitting = false; return; }
                        this.done = true; this.submitting = false;
                    })
                    .catch(() => { this.error = 'Terjadi kesalahan. Silakan coba lagi.'; this.submitting = false; });
                }
             ">
            <h2 class="font-bold text-ink-900 dark:text-white text-lg mb-2">Alami Gangguan?</h2>
            <p class="text-sm text-ink-500 dark:text-ink-400 mb-6">Laporkan gangguan yang Anda alami dan tim teknis kami akan segera menindaklanjuti.</p>

            <div x-show="!done">
                <form @submit.prevent="submitForm($event)" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <input type="text" name="reporter_name" placeholder="Nama Anda" required class="form-input text-sm">
                        <input type="text" name="reporter_phone" placeholder="Nomor HP/WhatsApp" required class="form-input text-sm">
                    </div>
                    <input type="text" name="region_name" placeholder="Wilayah/Kecamatan Anda" required class="form-input text-sm">
                    <input type="text" name="title" placeholder="Judul singkat (contoh: Internet putus total)" required class="form-input text-sm">
                    <textarea name="description" rows="3" placeholder="Jelaskan gangguan yang Anda alami..." required minlength="10" class="form-textarea text-sm"></textarea>

                    <div x-show="error" x-cloak class="alert-error text-xs"><span x-text="error"></span></div>

                    <button type="submit" :disabled="submitting" class="btn-primary">
                        <span x-text="submitting ? 'Mengirim...' : 'Kirim Laporan Gangguan'"></span>
                    </button>
                </form>
            </div>
            <div x-show="done" x-cloak class="alert-success">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div>
                    <p class="font-semibold">Laporan Terkirim!</p>
                    <p class="text-sm mt-1">Tim teknis kami akan segera menindaklanjuti laporan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
