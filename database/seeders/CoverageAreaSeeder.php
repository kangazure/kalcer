<?php

namespace Database\Seeders;

use App\Models\CoverageArea;
use App\Models\NetworkMonitor;
use Illuminate\Database\Seeder;

class CoverageAreaSeeder extends Seeder
{
    /**
     * Wilayah jangkauan dipetakan 1:1 terhadap 5 POP resmi PT JTS
     * (lihat NetworkMonitorSeeder). Radius merupakan estimasi jangkauan
     * layanan dari tiap POP -- sesuaikan dengan data real coverage
     * planning (RAB/desain jaringan) sebelum dipakai di production.
     */
    public function run(): void
    {
        $pop01 = NetworkMonitor::where('node_name', 'POP01 - Raman Utara (Server Utama)')->first();
        $pop02 = NetworkMonitor::where('node_name', 'POP02 - Seputih Banyak')->first();
        $pop03 = NetworkMonitor::where('node_name', 'POP03 - Kota Gajah')->first();
        $pop04 = NetworkMonitor::where('node_name', 'POP04 - Way Bungur')->first();
        $pop05 = NetworkMonitor::where('node_name', 'POP05 - Purbolinggo')->first();

        $areas = [
            [
                'region_name' => 'Raman Utara',
                'district' => 'Raman Utara',
                'regency' => 'Lampung Timur',
                'center_latitude' => -5.0667,
                'center_longitude' => 105.5333,
                'radius_meters' => 6000,
                'coverage_status' => 'available',
                'pop_id' => $pop01?->id,
            ],
            [
                'region_name' => 'Seputih Banyak',
                'district' => 'Seputih Banyak',
                'regency' => 'Lampung Tengah',
                'center_latitude' => -4.6800,
                'center_longitude' => 105.3500,
                'radius_meters' => 5000,
                'coverage_status' => 'available',
                'pop_id' => $pop02?->id,
            ],
            [
                'region_name' => 'Kota Gajah',
                'district' => 'Kota Gajah',
                'regency' => 'Lampung Tengah',
                'center_latitude' => -4.7000,
                'center_longitude' => 105.3000,
                'radius_meters' => 5000,
                'coverage_status' => 'available',
                'pop_id' => $pop03?->id,
            ],
            [
                'region_name' => 'Way Bungur',
                'district' => 'Way Bungur',
                'regency' => 'Lampung Timur',
                'center_latitude' => -5.0300,
                'center_longitude' => 105.6000,
                'radius_meters' => 4000,
                'coverage_status' => 'available',
                'pop_id' => $pop04?->id,
            ],
            [
                'region_name' => 'Purbolinggo',
                'district' => 'Purbolinggo',
                'regency' => 'Lampung Timur',
                'center_latitude' => -5.1200,
                'center_longitude' => 105.6100,
                'radius_meters' => 5000,
                'coverage_status' => 'available',
                'pop_id' => $pop05?->id,
            ],
        ];

        foreach ($areas as $area) {
            $area['is_active'] = true;
            CoverageArea::updateOrCreate(['region_name' => $area['region_name']], $area);
        }

        $this->command->info('Coverage areas seeded: '.count($areas).' wilayah (1:1 dengan 5 POP resmi).');
        $this->command->warn('[PLACEHOLDER] Radius & koordinat estimasi -- sesuaikan dengan data coverage planning aktual.');
    }
}
