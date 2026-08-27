<?php

return [
    'default' => env('CACHE_STORE', 'redis'),

    'stores' => [
        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'connection' => env('DB_CACHE_CONNECTION'),
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
            'lock_table' => env('DB_CACHE_LOCK_TABLE'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],
    ],

    'prefix' => env('CACHE_PREFIX', 'jts_cache_'),

    /*
    |--------------------------------------------------------------------------
    | TTL Preset (detik) — dipakai App\Services\CacheService untuk konsistensi
    | umur cache lintas modul (paket internet, layanan, FAQ, settings, dst).
    |--------------------------------------------------------------------------
    */
    'ttl_presets' => [
        'short' => 60 * 5,        // 5 menit — data yang sering berubah (network status)
        'medium' => 60 * 60,      // 1 jam — paket, layanan, FAQ
        'long' => 60 * 60 * 24,   // 1 hari — settings, halaman statis
        'very_long' => 60 * 60 * 24 * 7, // 1 minggu — sitemap, konten arsip
    ],
];
