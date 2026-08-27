<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            ContentSeeder::class, // Slider, Banner, Team, Testimonial, FAQ, Category, Tag, Post, Portfolio, Gallery, Career
            NetworkMonitorSeeder::class,
            CoverageAreaSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
