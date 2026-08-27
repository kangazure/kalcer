<?php

namespace Database\Seeders;

use App\Models\NetworkMonitor;
use Illuminate\Database\Seeder;

class NetworkMonitorSeeder extends Seeder
{
    /**
     * Data POP (Point of Presence) resmi PT JTS.
     *
     * POP01 (Raman Utara) berfungsi sebagai Server Utama / core router yang
     * menghubungkan seluruh POP lain -- karena itu node_type-nya 'core',
     * sedangkan POP02-POP05 adalah node_type 'pop' dengan parent_node_id
     * menunjuk ke POP01.
     *
     * CATATAN: Koordinat GPS di bawah ini adalah estimasi berdasarkan lokasi
     * umum kecamatan (belum GPS presisi titik tower/perangkat). Ganti dengan
     * koordinat aktual perangkat sebelum digunakan untuk kalkulasi cek
     * jangkauan yang akurat di production.
     */
    public function run(): void
    {
        $nodes = [
            [
                'node_name' => 'POP01 - Raman Utara (Server Utama)',
                'node_type' => 'core',
                'ip_address' => '10.0.0.1',
                'latitude' => -5.0667,
                'longitude' => 105.5333,
                'status' => 'online',
                'bandwidth_capacity_mbps' => 1000,
                'uptime_percent' => 99.9,
            ],
            [
                'node_name' => 'POP02 - Seputih Banyak',
                'node_type' => 'pop',
                'ip_address' => '10.0.2.1',
                'latitude' => -4.6800,
                'longitude' => 105.3500,
                'status' => 'online',
                'bandwidth_capacity_mbps' => 300,
                'uptime_percent' => 99.5,
            ],
            [
                'node_name' => 'POP03 - Kota Gajah',
                'node_type' => 'pop',
                'ip_address' => '10.0.3.1',
                'latitude' => -4.7000,
                'longitude' => 105.3000,
                'status' => 'online',
                'bandwidth_capacity_mbps' => 300,
                'uptime_percent' => 99.3,
            ],
            [
                'node_name' => 'POP04 - Way Bungur',
                'node_type' => 'pop',
                'ip_address' => '10.0.4.1',
                'latitude' => -5.0300,
                'longitude' => 105.6000,
                'status' => 'online',
                'bandwidth_capacity_mbps' => 200,
                'uptime_percent' => 99.0,
            ],
            [
                'node_name' => 'POP05 - Purbolinggo',
                'node_type' => 'pop',
                'ip_address' => '10.0.5.1',
                'latitude' => -5.1200,
                'longitude' => 105.6100,
                'status' => 'online',
                'bandwidth_capacity_mbps' => 300,
                'uptime_percent' => 99.5,
            ],
        ];

        $created = [];
        foreach ($nodes as $node) {
            $created[$node['node_name']] = NetworkMonitor::updateOrCreate(
                ['node_name' => $node['node_name']],
                $node
            );
        }

        // POP02-POP05 terhubung ke POP01 (Server Utama) sebagai parent node.
        $serverUtama = $created['POP01 - Raman Utara (Server Utama)'];
        foreach ($created as $name => $node) {
            if ($name !== 'POP01 - Raman Utara (Server Utama)') {
                $node->update(['parent_node_id' => $serverUtama->id]);
            }
        }

        $this->command->info('Network monitor nodes seeded: '.count($created).' POP resmi JTS.');
        $this->command->warn('[PLACEHOLDER] Koordinat GPS estimasi kecamatan -- verifikasi dengan titik lokasi perangkat aktual.');
    }
}
