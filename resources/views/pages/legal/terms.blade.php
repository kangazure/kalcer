@extends('layouts.app')
@push('seo_title')Syarat & Ketentuan — PT Jaringan Teknologi Sejahtera@endpush
@push('seo_description')Syarat dan ketentuan penggunaan layanan internet PT Jaringan Teknologi Sejahtera.@endpush

@section('content')
<section class="page-hero py-16">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="section-label" data-aos="fade-down">Legal</span>
        <h1 class="page-hero-title" data-aos="fade-up">Syarat &amp; Ketentuan</h1>
        <p class="text-ink-500 text-sm mt-3">Terakhir diperbarui: {{ now()->isoFormat('D MMMM YYYY') }}</p>
    </div>
</section>

<section class="py-16 bg-white dark:bg-ink-950">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6 lg:px-8 prose-jts">
        <p>Dengan menggunakan layanan PT Jaringan Teknologi Sejahtera ("JTS"), Anda setuju untuk terikat dengan Syarat dan Ketentuan berikut ini.</p>

        <h2>1. Layanan</h2>
        <p>JTS menyediakan layanan akses internet melalui jaringan fiber optik dengan berbagai paket sesuai kebutuhan pelanggan (rumah, bisnis, dedicated, metro ethernet, dan lainnya). Kecepatan dan kualitas layanan dapat bervariasi tergantung kondisi jaringan dan lokasi pelanggan.</p>

        <h2>2. Pendaftaran &amp; Instalasi</h2>
        <p>Pendaftaran layanan tunduk pada verifikasi jangkauan area dan survei lokasi oleh tim teknis JTS. JTS berhak menolak pendaftaran apabila lokasi belum terjangkau infrastruktur jaringan.</p>

        <h2>3. Pembayaran</h2>
        <p>Pelanggan wajib membayar tagihan sesuai paket yang dipilih pada tanggal jatuh tempo yang ditentukan. Keterlambatan pembayaran dapat mengakibatkan penangguhan layanan sementara hingga denda administratif sesuai kebijakan yang berlaku.</p>

        <h2>4. Penggunaan yang Wajar</h2>
        <p>Pelanggan dilarang menggunakan layanan untuk aktivitas ilegal, termasuk namun tidak terbatas pada penyebaran konten yang melanggar hukum, peretasan, atau aktivitas yang merugikan pihak lain maupun infrastruktur jaringan JTS.</p>

        <h2>5. Gangguan Layanan</h2>
        <p>JTS berupaya menjaga keandalan jaringan dengan target uptime yang telah ditetapkan, namun tidak dapat menjamin layanan bebas dari gangguan sepenuhnya, termasuk gangguan akibat force majeure, bencana alam, atau gangguan pihak ketiga.</p>

        <h2>6. Pembatalan Layanan</h2>
        <p>Pelanggan dapat mengajukan pembatalan layanan dengan pemberitahuan tertulis sesuai ketentuan masa kontrak yang berlaku. Perangkat milik JTS (ONT, router) wajib dikembalikan dalam kondisi baik setelah pembatalan.</p>

        <h2>7. Perubahan Ketentuan</h2>
        <p>JTS berhak mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan melalui website resmi kami.</p>

        <h2>8. Hukum yang Berlaku</h2>
        <p>Syarat dan Ketentuan ini tunduk pada hukum yang berlaku di Republik Indonesia.</p>

        <h2>9. Kontak</h2>
        <p>Pertanyaan mengenai Syarat dan Ketentuan ini dapat diajukan melalui halaman <a href="{{ route('contact.index') }}">Kontak</a> kami.</p>
    </div>
</section>
@endsection
