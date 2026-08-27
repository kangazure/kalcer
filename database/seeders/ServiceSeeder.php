<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Internet Rumah', 'slug' => 'internet-rumah', 'icon' => 'home-wifi',
                'short_description' => 'Koneksi internet fiber optik cepat dan stabil untuk kebutuhan rumah tangga Anda.',
                'description' => "# Internet Rumah JTS\n\nLayanan Internet Rumah JTS menghadirkan koneksi fiber optik berkecepatan tinggi untuk kebutuhan rumah tangga modern. Nikmati streaming tanpa buffering, gaming online tanpa lag, dan video conference yang jernih.",
                'features' => ['Fiber Optik GPON', 'Unlimited tanpa FUP', 'Router WiFi 6 Gratis', 'Instalasi Gratis', 'Dukungan Teknis 24/7'],
                'benefits' => ['Streaming 4K Ultra HD', 'Gaming Online Tanpa Lag', 'Video Call Jernih', 'WFH Lebih Produktif'],
                'sort_order' => 1, 'is_featured_home' => true],
            ['name' => 'Internet Bisnis', 'slug' => 'internet-bisnis', 'icon' => 'building-wifi',
                'short_description' => 'Koneksi internet handal untuk mendukung operasional bisnis Anda dengan SLA yang terjamin.',
                'description' => "# Internet Bisnis JTS\n\nDirancang untuk mendukung operasional bisnis UMKM hingga perusahaan skala menengah dengan koneksi andal dan SLA tertulis.",
                'features' => ['SLA 99.5% Uptime', 'Static IP Address', 'Bandwidth Dedicated', 'Prioritas Teknis 24/7'],
                'benefits' => ['Operasional Tanpa Gangguan', 'Transaksi Online Aman', 'Cloud Computing Lancar'],
                'sort_order' => 2, 'is_featured_home' => true],
            ['name' => 'Dedicated Internet', 'slug' => 'dedicated-internet', 'icon' => 'server-wifi',
                'short_description' => 'Bandwidth penuh dedicated khusus untuk Anda — tidak berbagi dengan pengguna lain.',
                'description' => "# Dedicated Internet JTS\n\nBandwidth penuh 1:1 ratio, ideal untuk perusahaan dengan kebutuhan bandwidth tinggi dan konsisten.",
                'features' => ['1:1 Bandwidth Ratio', 'SLA 99.9% Uptime', 'BGP Routing', 'Multiple Static IP'],
                'benefits' => ['Performa Konsisten', 'Latency Rendah', 'Cocok untuk Data Center'],
                'sort_order' => 3, 'is_featured_home' => true],
            ['name' => 'Metro Ethernet', 'slug' => 'metro-ethernet', 'icon' => 'network-wired',
                'short_description' => 'Koneksi LAN privat berkecepatan tinggi antar lokasi bisnis Anda di wilayah Lampung Timur.',
                'description' => "# Metro Ethernet JTS\n\nMenghubungkan beberapa lokasi bisnis dalam satu jaringan privat berkecepatan tinggi.",
                'features' => ['Point-to-Point', 'VLAN Private', 'Kecepatan hingga 10 Gbps'],
                'benefits' => ['Kantor Cabang Terhubung', 'File Sharing Cepat'],
                'sort_order' => 4, 'is_featured_home' => false],
            ['name' => 'Fiber Optik', 'slug' => 'fiber-optik', 'icon' => 'cable',
                'short_description' => 'Infrastruktur fiber optik terdepan dengan teknologi GPON dan XGS-PON untuk performa terbaik.',
                'description' => "# Infrastruktur Fiber Optik JTS\n\nMenggunakan teknologi GPON dan XGS-PON siap menghadirkan kecepatan hingga 10 Gbps.",
                'features' => ['Teknologi GPON/XGS-PON', 'Kabel Single Mode', 'Jarak Jangkauan hingga 20km'],
                'benefits' => ['Kecepatan Simetris', 'Latensi Sangat Rendah'],
                'sort_order' => 5, 'is_featured_home' => false],
            ['name' => 'Cloud', 'slug' => 'cloud', 'icon' => 'cloud',
                'short_description' => 'Layanan cloud hosting, VPS, dan solusi cloud computing untuk bisnis Anda.',
                'description' => "# Layanan Cloud JTS\n\nSolusi cloud computing yang dapat disesuaikan dengan kebutuhan bisnis Anda.",
                'features' => ['VPS SSD NVMe', 'Cloud Hosting', 'Object Storage', 'Auto Backup Daily'],
                'benefits' => ['Skalabilitas Mudah', 'Bayar Sesuai Pakai'],
                'sort_order' => 6, 'is_featured_home' => false],
            ['name' => 'Data Center', 'slug' => 'data-center', 'icon' => 'server',
                'short_description' => 'Fasilitas data center tier II dengan sistem pendingin dan kelistrikan redundan.',
                'description' => "# Data Center JTS\n\nDirancang dengan standar keamanan dan keandalan tinggi.",
                'features' => ['Tier II Infrastructure', 'Pendingin Redundan N+1', 'Listrik UPS + Genset'],
                'benefits' => ['Uptime 99.9%', 'Keamanan Fisik Tinggi'],
                'sort_order' => 7, 'is_featured_home' => false],
            ['name' => 'Colocation', 'slug' => 'colocation', 'icon' => 'rack-server',
                'short_description' => 'Tempatkan server Anda di data center JTS dan nikmati konektivitas backbone langsung.',
                'description' => "# Colocation JTS\n\nMenempatkan perangkat server di fasilitas data center JTS yang aman dan terhubung langsung ke backbone.",
                'features' => ['Space 1U-Full Rack', 'Power Dedicated', 'Remote Hands Service'],
                'benefits' => ['Hemat Biaya Infrastruktur', 'Kontrol Penuh Perangkat'],
                'sort_order' => 8, 'is_featured_home' => false],
            ['name' => 'Managed Service', 'slug' => 'managed-service', 'icon' => 'headset',
                'short_description' => 'Tim ahli JTS mengelola infrastruktur IT Anda sepenuhnya, sehingga Anda fokus pada bisnis.',
                'description' => "# Managed Service JTS\n\nTim ahli NOC kami memantau, memelihara, dan mengoptimalkan sistem Anda 24/7.",
                'features' => ['Monitoring 24/7', 'Patch Management', 'Backup Terkelola'],
                'benefits' => ['Fokus ke Core Bisnis', 'Hemat Biaya IT Staff'],
                'sort_order' => 9, 'is_featured_home' => false],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['slug' => $service['slug']], $service);
        }

        $this->command->info('Services seeded: '.count($services).' entries.');
    }
}
