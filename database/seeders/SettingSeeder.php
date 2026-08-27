<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Catatan: pengaturan inti (company_legal_name, alamat, telepon, dsb)
     * sudah di-seed via SQL migration 0014_seed_initial_data.sql di
     * Supabase. Seeder ini hanya menambahkan pengaturan pelengkap yang
     * dipakai tampilan (counter homepage, social link tambahan).
     */
    public function run(): void
    {
        $settings = [
            ['group_name' => 'general', 'key' => 'social_instagram', 'value' => '"https://instagram.com/ptjts.id"', 'label' => 'Instagram', 'is_public' => true],
            ['group_name' => 'general', 'key' => 'social_facebook', 'value' => '"https://facebook.com/ptjts.id"', 'label' => 'Facebook', 'is_public' => true],
            ['group_name' => 'general', 'key' => 'total_customers', 'value' => '"500+"', 'label' => 'Total Pelanggan (Counter Hero)', 'is_public' => true],
            ['group_name' => 'general', 'key' => 'total_cities', 'value' => '"5"', 'label' => 'Titik POP Terjangkau', 'is_public' => true],
            ['group_name' => 'general', 'key' => 'network_uptime', 'value' => '"99.9%"', 'label' => 'Network Uptime', 'is_public' => true],
            ['group_name' => 'seo', 'key' => 'default_meta_title', 'value' => '"PT Jaringan Teknologi Sejahtera (JTS) — ISP Lampung Timur"', 'label' => 'Default Meta Title', 'is_public' => true],
            ['group_name' => 'seo', 'key' => 'default_meta_description', 'value' => '"JTS adalah penyedia internet fiber optik cepat dan andal untuk rumah dan bisnis di Lampung Timur."', 'label' => 'Default Meta Description', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['group_name' => $setting['group_name'], 'key' => $setting['key']], $setting);
        }

        $this->command->info('Settings seeded: '.count($settings).' additional entries.');
    }
}
