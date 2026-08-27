@extends('layouts.app')
@push('seo_title')Kebijakan Privasi — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Kebijakan privasi PT Jaringan Teknologi Sejahtera terkait pengumpulan, penggunaan, dan perlindungan data pribadi pelanggan.@endpush

@section('content')
<section class="page-hero py-16">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Legal</span>
        <h1 class="page-hero-title" data-aos="fade-up">Kebijakan Privasi</h1>
        <p class="text-ink-500 text-sm mt-3">Terakhir diperbarui: {{ now()->isoFormat('D MMMM YYYY') }}</p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8 prose-jts">
        <p>PT Jaringan Teknologi Sejahtera ("JTS", "kami") berkomitmen untuk melindungi privasi dan data pribadi pelanggan serta pengunjung website kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>

        <h2>1. Informasi yang Kami Kumpulkan</h2>
        <p>Kami dapat mengumpulkan informasi berikut ketika Anda menggunakan layanan kami:</p>
        <ul>
            <li>Nama lengkap, alamat email, dan nomor telepon saat Anda mengisi formulir kontak, pendaftaran, atau lamaran kerja.</li>
            <li>Alamat pemasangan dan koordinat lokasi saat Anda menggunakan fitur cek jangkauan.</li>
            <li>Data teknis seperti alamat IP, jenis perangkat, dan perilaku penjelajahan untuk keperluan analitik website.</li>
            <li>Dokumen yang diunggah (seperti CV) saat melamar posisi pekerjaan.</li>
        </ul>

        <h2>2. Penggunaan Informasi</h2>
        <p>Informasi yang kami kumpulkan digunakan untuk:</p>
        <ul>
            <li>Memproses pendaftaran layanan internet dan permintaan cek jangkauan.</li>
            <li>Menghubungi Anda terkait pertanyaan, keluhan, atau proses lamaran kerja.</li>
            <li>Meningkatkan kualitas layanan dan pengalaman pengguna website.</li>
            <li>Mengirimkan informasi promosi atau newsletter, hanya jika Anda telah menyetujui berlangganan.</li>
        </ul>

        <h2>3. Perlindungan Data</h2>
        <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang wajar untuk melindungi data pribadi Anda dari akses tidak sah, kehilangan, atau penyalahgunaan, termasuk enkripsi data sensitif dan kontrol akses berbasis peran pada sistem internal kami.</p>

        <h2>4. Berbagi Informasi dengan Pihak Ketiga</h2>
        <p>Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data hanya dibagikan kepada penyedia layanan pendukung operasional (seperti penyedia hosting/database) yang terikat kewajiban kerahasiaan.</p>

        <h2>5. Hak Anda</h2>
        <p>Anda berhak untuk mengakses, memperbarui, atau meminta penghapusan data pribadi Anda yang kami simpan, dengan menghubungi kami melalui kontak yang tersedia di website ini.</p>

        <h2>6. Cookie</h2>
        <p>Website kami menggunakan cookie untuk meningkatkan pengalaman pengguna dan menganalisis lalu lintas website. Anda dapat mengatur preferensi cookie melalui pengaturan browser Anda.</p>

        <h2>7. Perubahan Kebijakan</h2>
        <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan diinformasikan melalui halaman ini dengan tanggal pembaruan terbaru.</p>

        <h2>8. Hubungi Kami</h2>
        <p>Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini, silakan hubungi kami melalui email <a href="mailto:info@ptjts.id">info@ptjts.id</a> atau melalui halaman <a href="{{ route('contact.index') }}">Kontak</a>.</p>
    </div>
</section>
@endsection
