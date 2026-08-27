<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Tag;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder gabungan untuk seluruh konten placeholder: Slider, Team,
 * Testimonial, FAQ, Category, Tag, Post, Portfolio, Gallery, Career.
 * Digabung dalam satu file untuk kemudahan maintenance (semua data
 * "contoh yang jelas placeholder" sesuai keputusan desain proyek).
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSliders();
        $this->seedTeam();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedCategoriesAndTags();
        $this->seedPosts();
        $this->seedPortfolio();
        $this->seedGallery();
        $this->seedCareers();
    }

    protected function seedSliders(): void
    {
        $sliders = [
            ['title' => 'Internet Fiber Optik Tercepat di Lampung Timur', 'subtitle' => 'Teknologi GPON Terkini', 'description' => 'Nikmati kecepatan hingga 100 Mbps tanpa batas untuk rumah dan bisnis Anda.', 'image_url' => '/images/slides/slide-hero-1.jpg', 'cta_label' => 'Lihat Paket Internet', 'cta_url' => '/paket-internet', 'sort_order' => 1],
            ['title' => 'Solusi Internet Bisnis yang Andal', 'subtitle' => 'SLA 99.9% Uptime Terjamin', 'description' => 'Dukung operasional bisnis Anda dengan internet dedicated bergaransi uptime.', 'image_url' => '/images/slides/slide-hero-2.jpg', 'cta_label' => 'Konsultasi Gratis', 'cta_url' => '/kontak', 'sort_order' => 2],
            ['title' => 'Cek Jangkauan di Lokasi Anda', 'subtitle' => 'Sedang Ekspansi ke Seluruh Lampung Timur', 'description' => 'Masukkan alamat Anda sekarang dan cek jangkauan layanan JTS.', 'image_url' => '/images/slides/slide-hero-3.jpg', 'cta_label' => 'Cek Jangkauan', 'cta_url' => '/coverage-area', 'sort_order' => 3],
        ];
        foreach ($sliders as $s) {
            Slider::updateOrCreate(['title' => $s['title']], array_merge($s, ['is_active' => true]));
        }
        $this->command->info('Sliders seeded: '.count($sliders));
    }

    protected function seedTeam(): void
    {
        $team = [
            ['name' => '[PLACEHOLDER] Direktur Utama', 'position' => 'Direktur Utama', 'department' => 'Direksi', 'is_management' => true, 'sort_order' => 1],
            ['name' => '[PLACEHOLDER] Direktur Teknis', 'position' => 'Direktur Teknis', 'department' => 'Direksi', 'is_management' => true, 'sort_order' => 2],
            ['name' => '[PLACEHOLDER] General Manager', 'position' => 'General Manager', 'department' => 'Manajemen', 'is_management' => true, 'sort_order' => 3],
            ['name' => '[PLACEHOLDER] Network Engineer', 'position' => 'Senior Network Engineer', 'department' => 'Network Operations', 'sort_order' => 4],
            ['name' => '[PLACEHOLDER] IT Support', 'position' => 'IT Support Specialist', 'department' => 'Technical Support', 'sort_order' => 5],
            ['name' => '[PLACEHOLDER] Customer Service', 'position' => 'Customer Service Representative', 'department' => 'Customer Relations', 'sort_order' => 6],
        ];
        foreach ($team as $m) {
            Team::updateOrCreate(['name' => $m['name']], array_merge($m, ['is_active' => true]));
        }
        $this->command->info('Team seeded: '.count($team).' placeholder entries.');
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            ['customer_name' => '[PLACEHOLDER] Bapak Ahmad Sutrisno', 'customer_role' => 'Pemilik Warung Makan, Raman Utara', 'rating' => 5, 'content' => 'Alhamdulillah, sejak pasang internet JTS, QRIS dan pembayaran digital di warung saya jadi lancar. Tidak pernah putus-putus. Harga terjangkau, teknisinya cepat.', 'is_featured' => true, 'sort_order' => 1],
            ['customer_name' => '[PLACEHOLDER] Ibu Sari Wulandari', 'customer_role' => 'Guru SD, Purbolinggo', 'rating' => 5, 'content' => 'Koneksi stabil banget untuk Zoom meeting dengan orang tua murid. Anak-anak di rumah juga happy belajar online tanpa loading lama.', 'is_featured' => true, 'sort_order' => 2],
            ['customer_name' => '[PLACEHOLDER] Pak Hendra Gunawan', 'customer_role' => 'Pemilik Toko Online, Way Bungur', 'rating' => 5, 'content' => 'Bisnis toko online saya makin lancar setelah ganti ke JTS. Upload foto produk cepat, live streaming marketplace tanpa hambatan.', 'is_featured' => true, 'sort_order' => 3],
            ['customer_name' => '[PLACEHOLDER] CV Maju Bersama', 'customer_role' => 'Perusahaan Distribusi, Seputih Banyak', 'rating' => 5, 'content' => 'Sebagai pelanggan bisnis JTS, kami puas dengan SLA yang dijanjikan. Uptime terbukti bagus, tim NOC responsif.', 'sort_order' => 4],
            ['customer_name' => '[PLACEHOLDER] Muhammad Ridwan', 'customer_role' => 'Content Creator, Kota Gajah', 'rating' => 4, 'content' => 'Upload video YouTube jadi jauh lebih cepat. Dulu 1 video bisa 2 jam, sekarang 20 menit sudah selesai.', 'sort_order' => 5],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['customer_name' => $t['customer_name']], array_merge($t, ['is_published' => true]));
        }
        $this->command->info('Testimonials seeded: '.count($testimonials).' placeholder entries.');
    }

    protected function seedFaqs(): void
    {
        $faqs = [
            ['category' => 'Pendaftaran', 'question' => 'Bagaimana cara mendaftar layanan internet JTS?', 'answer' => "Anda dapat mendaftar melalui:\n1. Website: isi formulir di halaman Kontak\n2. WhatsApp: +62 821-8399-9981\n3. Datang langsung ke kantor kami di Desa Rukti Sedyo, Raman Utara.\n\nTim kami akan menghubungi Anda dalam 1x24 jam.", 'sort_order' => 1],
            ['category' => 'Pendaftaran', 'question' => 'Berapa biaya instalasi internet JTS?', 'answer' => 'Biaya instalasi untuk semua paket internet rumah adalah GRATIS. Untuk paket bisnis dan dedicated, biaya instalasi juga gratis dengan kontrak minimal 1 tahun.', 'sort_order' => 2],
            ['category' => 'Teknis', 'question' => 'Teknologi apa yang digunakan JTS untuk fiber optik?', 'answer' => 'JTS menggunakan teknologi GPON (Gigabit Passive Optical Network), standar fiber optik generasi terkini dengan kecepatan hingga 1 Gbps dan latensi sangat rendah.', 'sort_order' => 1],
            ['category' => 'Teknis', 'question' => 'Apakah ada FUP (Fair Usage Policy)?', 'answer' => 'Seluruh paket internet rumah dan bisnis JTS adalah UNLIMITED tanpa FUP. Gunakan internet sepuasnya 24 jam tanpa khawatir kecepatan diperlambat.', 'sort_order' => 2],
            ['category' => 'Tagihan', 'question' => 'Apa saja metode pembayaran yang tersedia?', 'answer' => "JTS menerima:\n- Transfer Bank (BCA, BRI, BNI, Mandiri)\n- QRIS\n- Minimarket (Alfamart, Indomaret)\n- Cash langsung ke kantor", 'sort_order' => 1],
            ['category' => 'Gangguan', 'question' => 'Apa yang harus dilakukan jika internet tiba-tiba tidak terhubung?', 'answer' => "1. Restart router (cabut power 30 detik)\n2. Cek indikator lampu ONT\n3. Cek halaman Status Jaringan website kami\n4. Hubungi helpdesk +62 821-8399-9981 (24/7)", 'sort_order' => 1],
            ['category' => 'Umum', 'question' => 'Apakah JTS melayani area di luar Lampung Timur?', 'answer' => 'Saat ini JTS melayani 5 titik POP: Raman Utara (Lampung Timur), Way Bungur (Lampung Timur), Purbolinggo (Lampung Timur), Seputih Banyak (Lampung Tengah), dan Kota Gajah (Lampung Tengah). Gunakan fitur Cek Jangkauan untuk memastikan lokasi Anda.', 'sort_order' => 1],
        ];
        foreach ($faqs as $f) {
            Faq::updateOrCreate(['question' => $f['question']], array_merge($f, ['is_active' => true]));
        }
        $this->command->info('FAQs seeded: '.count($faqs));
    }

    protected function seedCategoriesAndTags(): void
    {
        $categories = [
            ['name' => 'Berita & Pengumuman', 'slug' => 'berita', 'sort_order' => 1],
            ['name' => 'Tips & Tutorial', 'slug' => 'tips-tutorial', 'sort_order' => 2],
            ['name' => 'Teknologi Internet', 'slug' => 'teknologi-internet', 'sort_order' => 3],
            ['name' => 'Bisnis & UMKM', 'slug' => 'bisnis-umkm', 'sort_order' => 4],
            ['name' => 'Komunitas', 'slug' => 'komunitas', 'sort_order' => 5],
        ];
        foreach ($categories as $c) {
            Category::updateOrCreate(['slug' => $c['slug']], array_merge($c, ['is_active' => true]));
        }

        $tags = ['Fiber Optik', 'Internet Rumah', 'Internet Bisnis', 'GPON', 'WiFi', 'Teknologi', 'UMKM', 'Lampung Timur', 'Tips Internet', 'Cloud'];
        foreach ($tags as $tag) {
            Tag::updateOrCreate(['slug' => Str::slug($tag)], ['name' => $tag, 'slug' => Str::slug($tag)]);
        }

        $this->command->info('Categories seeded: '.count($categories).', Tags seeded: '.count($tags));
    }

    protected function seedPosts(): void
    {
        $author = User::where('email', 'editor@ptjts.id')->first();
        $category = Category::where('slug', 'teknologi-internet')->first();

        $posts = [
            [
                'category_id' => $category?->id, 'author_id' => $author?->id,
                'title' => 'Mengenal Teknologi Fiber Optik GPON: Revolusi Internet di Lampung Timur',
                'slug' => 'mengenal-teknologi-fiber-optik-gpon',
                'excerpt' => 'Fiber optik GPON telah mengubah cara masyarakat Lampung Timur mengakses internet.',
                'content' => "# Mengenal Teknologi Fiber Optik GPON\n\nGPON (Gigabit Passive Optical Network) adalah teknologi transmisi data menggunakan cahaya sebagai medium penghantar.\n\n## Keunggulan Utama GPON\n\n**Kecepatan Tinggi dan Simetris** — hingga 2.5 Gbps downstream.\n\n**Latensi Ultra Rendah** — di bawah 5ms, ideal untuk gaming dan video conference.\n\n**Tidak Ada Interferensi Elektromagnetik** — berbeda dengan kabel tembaga.\n\n## GPON di Lampung Timur\n\nJTS telah membangun infrastruktur GPON yang menjangkau lebih dari 8 kecamatan di Lampung Timur.",
                'status' => 'published', 'is_featured' => true, 'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'category_id' => Category::where('slug', 'bisnis-umkm')->first()?->id, 'author_id' => $author?->id,
                'title' => '5 Cara Internet Cepat Mengubah Bisnis UMKM di Lampung Timur',
                'slug' => '5-cara-internet-cepat-mengubah-bisnis-umkm-lampung-timur',
                'excerpt' => 'Internet cepat telah menjadi motor penggerak UMKM di Kabupaten Lampung Timur.',
                'content' => "# 5 Cara Internet Cepat Mengubah Bisnis UMKM\n\n## 1. Pembayaran Digital Tanpa Hambatan\n\nQRIS dan transfer bank kini bisa diandalkan tanpa lag.\n\n## 2. Berjualan di E-Commerce\n\nUpload foto produk dan balas chat pembeli lebih cepat.\n\n## 3. Live Streaming untuk Jualan\n\nDengan paket bisnis 20-100 Mbps simetris, live streaming lancar.\n\n## 4. Cloud Computing untuk Efisiensi\n\nAplikasi kasir digital berbasis cloud berjalan optimal.\n\n## 5. Video Conference dengan Klien\n\nMeeting dengan supplier luar kota tanpa koneksi putus.",
                'status' => 'published', 'is_featured' => true, 'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'category_id' => Category::where('slug', 'tips-tutorial')->first()?->id, 'author_id' => $author?->id,
                'title' => 'Tips Mengoptimalkan WiFi di Rumah',
                'slug' => 'tips-mengoptimalkan-wifi-di-rumah',
                'excerpt' => 'Sudah berlangganan internet cepat tapi WiFi terasa lambat? Berikut tips praktisnya.',
                'content' => "# Tips Mengoptimalkan WiFi di Rumah\n\n## 1. Posisikan Router di Tempat Strategis\n\nDi tempat terbuka, pusat rumah, jauh dari perangkat elektronik lain.\n\n## 2. Pilih Channel WiFi yang Tidak Padat\n\nGunakan aplikasi WiFi Analyzer untuk menemukan channel yang sepi.\n\n## 3. Aktifkan Frekuensi 5 GHz\n\nUntuk perangkat yang dekat dengan router.\n\n## 4. Update Firmware Router\n\nSecara berkala untuk perbaikan bug dan performa.\n\n## 5. Pertimbangkan WiFi Mesh\n\nUntuk rumah luas atau bertingkat.",
                'status' => 'published', 'published_at' => Carbon::now()->subDays(15),
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post);
        }
        $this->command->info('Posts seeded: '.count($posts));
    }

    protected function seedPortfolio(): void
    {
        $portfolios = [
            ['title' => 'Jaringan Internet untuk 500 Pelanggan Perumahan Griya Sejahtera', 'slug' => 'jaringan-perumahan-griya-sejahtera', 'client_name' => '[PLACEHOLDER] Developer Griya Sejahtera', 'category' => 'Internet Rumah', 'location' => 'Raman Utara', 'summary' => 'Pemasangan infrastruktur fiber optik GPON untuk 500 unit rumah.', 'result_metric_label' => 'Unit Pelanggan Aktif', 'result_metric_value' => '500+', 'project_year' => 2024, 'is_featured' => true, 'sort_order' => 1],
            ['title' => 'Koneksi Internet Dedicated untuk Pabrik Pengolahan Singkong', 'slug' => 'internet-dedicated-pabrik-singkong', 'client_name' => '[PLACEHOLDER] CV Singkong Lampung Makmur', 'category' => 'Dedicated Internet', 'location' => 'Purbolinggo', 'summary' => 'Implementasi koneksi dedicated 20 Mbps untuk sistem ERP dan CCTV 24 jam.', 'result_metric_label' => 'Peningkatan Efisiensi', 'result_metric_value' => '25%', 'project_year' => 2024, 'is_featured' => true, 'sort_order' => 2],
            ['title' => 'Metro Ethernet Menghubungkan 5 Kantor Cabang Koperasi', 'slug' => 'metro-ethernet-koperasi-5-cabang', 'client_name' => '[PLACEHOLDER] Koperasi Tani Maju Bersama', 'category' => 'Metro Ethernet', 'location' => 'Lampung Timur', 'summary' => 'Metro Ethernet P2MP menghubungkan kantor pusat dan 4 cabang.', 'result_metric_label' => 'Kantor Terhubung', 'result_metric_value' => '5 Lokasi', 'project_year' => 2024, 'is_featured' => true, 'sort_order' => 3],
        ];
        foreach ($portfolios as $p) {
            Portfolio::updateOrCreate(['slug' => $p['slug']], array_merge($p, ['is_published' => true]));
        }
        $this->command->info('Portfolio seeded: '.count($portfolios));
    }

    protected function seedGallery(): void
    {
        $galleries = [
            ['title' => 'Proses Instalasi Fiber Optik — Raman Utara', 'slug' => 'instalasi-fiber-raman-utara', 'category' => 'Instalasi', 'sort_order' => 1],
            ['title' => 'Kegiatan HUT PT JTS — Juni 2024', 'slug' => 'hut-jts-juni-2024', 'category' => 'Event', 'sort_order' => 2],
            ['title' => 'Pelatihan Teknis Tim NOC', 'slug' => 'pelatihan-teknis-tim-noc', 'category' => 'Kegiatan Internal', 'sort_order' => 3],
        ];
        foreach ($galleries as $g) {
            Gallery::updateOrCreate(['slug' => $g['slug']], array_merge($g, ['is_published' => true]));
        }
        $this->command->info('Gallery albums seeded: '.count($galleries));
    }

    protected function seedCareers(): void
    {
        $careers = [
            ['title' => 'Network Engineer', 'slug' => 'network-engineer-2024', 'department' => 'Network Operations', 'location' => 'Raman Utara, Lampung Timur', 'job_type' => 'full_time',
                'description' => 'Kami mencari Network Engineer berpengalaman untuk bergabung dengan tim NOC JTS.',
                'requirements' => ['Pengalaman minimal 2 tahun di bidang jaringan', 'Memahami GPON, FTTH, BGP', 'Familiar Mikrotik RouterOS', 'Bersedia on-call'],
                'responsibilities' => ['Monitoring NOC 24/7', 'Konfigurasi perangkat jaringan', 'Instalasi OLT/ODP'],
                'benefits' => ['Gaji kompetitif', 'BPJS Kesehatan & Ketenagakerjaan', 'Pelatihan dan sertifikasi'],
                'vacancy_count' => 2],
            ['title' => 'Teknisi Lapangan (Field Technician)', 'slug' => 'teknisi-lapangan-2024', 'department' => 'Technical Support', 'location' => 'Lampung Timur (Mobile)', 'job_type' => 'full_time',
                'description' => 'Teknisi lapangan bertugas melakukan instalasi, perawatan, dan perbaikan jaringan fiber optik.',
                'requirements' => ['Pendidikan minimal SMA/SMK', 'Memiliki SIM A/C aktif', 'Fisik sehat, tidak takut ketinggian'],
                'responsibilities' => ['Instalasi ONT dan router', 'Penarikan kabel drop fiber optik', 'Perbaikan gangguan lapangan'],
                'benefits' => ['Gaji pokok + insentif', 'BPJS Kesehatan & Ketenagakerjaan', 'Kendaraan operasional'],
                'vacancy_count' => 3],
            ['title' => 'Customer Service Representative', 'slug' => 'customer-service-2024', 'department' => 'Customer Relations', 'location' => 'Raman Utara, Lampung Timur', 'job_type' => 'full_time',
                'description' => 'Melayani pertanyaan dan keluhan pelanggan melalui WhatsApp, telepon, dan media sosial.',
                'requirements' => ['Pendidikan minimal SMA/D3', 'Komunikasi baik', 'Sabar dan ramah'],
                'responsibilities' => ['Menerima pertanyaan pelanggan', 'Eskalasi keluhan teknis', 'Follow-up kepuasan pelanggan'],
                'benefits' => ['Gaji UMK + insentif', 'BPJS Kesehatan & Ketenagakerjaan'],
                'vacancy_count' => 1, 'salary_is_negotiable' => false],
        ];

        foreach ($careers as $career) {
            $career['is_active'] = true;
            Career::updateOrCreate(['slug' => $career['slug']], $career);
        }
        $this->command->info('Careers seeded: '.count($careers).' placeholder entries.');
    }
}
