@extends('layouts.app')
@push('seo_title')Tentang Kami — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Kenali PT Jaringan Teknologi Sejahtera (JTS), penyedia layanan internet fiber optik terpercaya untuk rumah dan bisnis di Lampung.@endpush

@section('content')
<section class="page-hero">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Tentang Kami</span>
        <h1 class="page-hero-title mb-4" data-aos="fade-up">PT Jaringan Teknologi Sejahtera</h1>
        <p class="page-hero-subtitle max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Menghadirkan konektivitas internet fiber optik yang andal untuk mendukung pertumbuhan digital masyarakat dan pelaku usaha di Lampung.
        </p>
    </div>
</section>

<section class="py-20 bg-white dark:bg-ink-950">
    <div class="max-w-screen-lg mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
            <div data-aos="fade-right">
                <span class="section-label">Profil Perusahaan</span>
                <h2 class="text-2xl font-bold text-ink-900 dark:text-white mb-4">Berkomitmen Membangun Konektivitas Digital</h2>
                <p class="text-ink-600 dark:text-ink-400 leading-relaxed mb-4">
                    PT Jaringan Teknologi Sejahtera (JTS) adalah perusahaan penyedia jasa internet (Internet Service Provider) yang didirikan berdasarkan Akta Notaris No. 836 tanggal 6 Juni 2024 dari Notaris Santy Sagita, S.H., M.Kn., dan telah disahkan oleh Menteri Hukum RI dengan Nomor AHU-0111314.AH.01.11.TAHUN 2024.
                </p>
                <p class="text-ink-600 dark:text-ink-400 leading-relaxed">
                    Berbasis di Kabupaten Lampung Timur, JTS membangun infrastruktur fiber optik GPON yang menjangkau wilayah Lampung Timur dan Lampung Tengah, melayani kebutuhan internet rumah tangga, UMKM, hingga korporat.
                </p>
            </div>
            <div class="glass-card rounded-2xl p-8 border border-ink-100 dark:border-ink-800" data-aos="fade-left">
                <dl class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-ink-100 dark:border-ink-800 pb-3">
                        <dt class="text-ink-400">Nama Resmi</dt>
                        <dd class="text-ink-800 dark:text-ink-200 font-medium text-right">PT. Jaringan Teknologi Sejahtera</dd>
                    </div>
                    <div class="flex justify-between border-b border-ink-100 dark:border-ink-800 pb-3">
                        <dt class="text-ink-400">Tanggal Berdiri</dt>
                        <dd class="text-ink-800 dark:text-ink-200 font-medium">6 Juni 2024</dd>
                    </div>
                    <div class="flex justify-between border-b border-ink-100 dark:border-ink-800 pb-3">
                        <dt class="text-ink-400">Notaris</dt>
                        <dd class="text-ink-800 dark:text-ink-200 font-medium text-right">Santy Sagita, S.H., M.Kn.</dd>
                    </div>
                    <div class="flex justify-between border-b border-ink-100 dark:border-ink-800 pb-3">
                        <dt class="text-ink-400">No. Pengesahan</dt>
                        <dd class="text-ink-800 dark:text-ink-200 font-medium text-right text-xs">AHU-0111314.AH.01.11.2024</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-400">Kantor Pusat</dt>
                        <dd class="text-ink-800 dark:text-ink-200 font-medium text-right">Raman Utara, Lampung Timur</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-4 mb-20">
            <a href="{{ route('about.vision') }}" class="btn-outline">Visi &amp; Misi</a>
            <a href="{{ route('about.history') }}" class="btn-outline">Sejarah Perusahaan</a>
        </div>

        @if(isset($team) && $team->isNotEmpty())
        <div>
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="section-label">Tim Kami</span>
                <h2 class="section-title">Orang-Orang di Balik JTS</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($team as $index => $member)
                    <div class="text-center hover-card" data-aos="fade-up" data-aos-delay="{{ $index * 60 }}">
                        <div class="w-24 h-24 rounded-full bg-ink-100 dark:bg-ink-800 mx-auto mb-4 overflow-hidden flex items-center justify-center">
                            @if($member->photo_url)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-ink-400">{{ substr($member->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-ink-900 dark:text-white text-sm">{{ $member->name }}</h3>
                        <p class="text-xs text-ink-500">{{ $member->position }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
