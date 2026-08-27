<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Service;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $home = Service::where('slug', 'internet-rumah')->first();
        $biz = Service::where('slug', 'internet-bisnis')->first();
        $dedicated = Service::where('slug', 'dedicated-internet')->first();
        $metro = Service::where('slug', 'metro-ethernet')->first();

        // PLACEHOLDER: Harga estimasi realistis ISP lokal Lampung Timur. Ganti sebelum launch.
        $packages = [
            ['service_id' => $home?->id, 'category' => 'home', 'name' => 'JTS Home 10 Mbps', 'slug' => 'jts-home-10mbps', 'speed_mbps_download' => 10, 'speed_mbps_upload' => 10, 'price' => 150000, 'features' => ['Fiber Optik GPON', 'Unlimited', 'Router WiFi Gratis'], 'sort_order' => 1],
            ['service_id' => $home?->id, 'category' => 'home', 'name' => 'JTS Home 20 Mbps', 'slug' => 'jts-home-20mbps', 'speed_mbps_download' => 20, 'speed_mbps_upload' => 20, 'price' => 200000, 'price_promo' => 175000, 'features' => ['Fiber Optik GPON', 'Unlimited', 'Router WiFi 6 Gratis'], 'is_popular' => true, 'sort_order' => 2],
            ['service_id' => $home?->id, 'category' => 'home', 'name' => 'JTS Home 50 Mbps', 'slug' => 'jts-home-50mbps', 'speed_mbps_download' => 50, 'speed_mbps_upload' => 50, 'price' => 350000, 'features' => ['Fiber Optik GPON', 'Unlimited', '1 Static IP'], 'sort_order' => 3],
            ['service_id' => $home?->id, 'category' => 'home', 'name' => 'JTS Home 100 Mbps', 'slug' => 'jts-home-100mbps', 'speed_mbps_download' => 100, 'speed_mbps_upload' => 100, 'price' => 500000, 'features' => ['Fiber Optik GPON', 'Unlimited', 'Prioritas SLA'], 'sort_order' => 4],
            ['service_id' => $biz?->id, 'category' => 'business', 'name' => 'JTS Bisnis 20 Mbps', 'slug' => 'jts-bisnis-20mbps', 'speed_mbps_download' => 20, 'speed_mbps_upload' => 20, 'price' => 400000, 'features' => ['SLA 99.5%', '1 Static IP', 'Support 24/7'], 'sort_order' => 1],
            ['service_id' => $biz?->id, 'category' => 'business', 'name' => 'JTS Bisnis 50 Mbps', 'slug' => 'jts-bisnis-50mbps', 'speed_mbps_download' => 50, 'speed_mbps_upload' => 50, 'price' => 700000, 'features' => ['SLA 99.5%', '2 Static IP', 'Priority Queue'], 'is_popular' => true, 'sort_order' => 2],
            ['service_id' => $biz?->id, 'category' => 'business', 'name' => 'JTS Bisnis 100 Mbps', 'slug' => 'jts-bisnis-100mbps', 'speed_mbps_download' => 100, 'speed_mbps_upload' => 100, 'price' => 1200000, 'features' => ['SLA 99.7%', '4 Static IP', 'NOC Monitoring'], 'sort_order' => 3],
            ['service_id' => $dedicated?->id, 'category' => 'dedicated', 'name' => 'JTS Dedicated 10 Mbps', 'slug' => 'jts-dedicated-10mbps', 'speed_mbps_download' => 10, 'speed_mbps_upload' => 10, 'price' => 2000000, 'features' => ['1:1 Ratio', 'SLA 99.9%', 'BGP Routing'], 'sort_order' => 1],
            ['service_id' => $dedicated?->id, 'category' => 'dedicated', 'name' => 'JTS Dedicated 50 Mbps', 'slug' => 'jts-dedicated-50mbps', 'speed_mbps_download' => 50, 'speed_mbps_upload' => 50, 'price' => 8000000, 'features' => ['1:1 Ratio', 'SLA 99.9%', 'Redundansi Uplink'], 'sort_order' => 2],
            ['service_id' => $metro?->id, 'category' => 'metro_ethernet', 'name' => 'JTS Metro 10 Mbps (P2P)', 'slug' => 'jts-metro-10mbps-p2p', 'speed_mbps_download' => 10, 'speed_mbps_upload' => 10, 'price' => 1500000, 'features' => ['Point-to-Point', 'SLA 99.9%'], 'sort_order' => 1],
            ['service_id' => $metro?->id, 'category' => 'metro_ethernet', 'name' => 'JTS Metro 100 Mbps (P2P)', 'slug' => 'jts-metro-100mbps-p2p', 'speed_mbps_download' => 100, 'speed_mbps_upload' => 100, 'price' => 5000000, 'features' => ['Point-to-Point', 'Redundansi Fiber'], 'sort_order' => 2],
        ];

        foreach ($packages as $package) {
            $package['billing_cycle'] = 'monthly';
            $package['is_active'] = true;
            Package::updateOrCreate(['slug' => $package['slug']], $package);
        }

        $this->command->info('Packages seeded: '.count($packages).' entries.');
        $this->command->warn('[PLACEHOLDER] Harga paket bersifat estimasi. Ganti dengan harga resmi JTS.');
    }
}
