<?php

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // Disk khusus dokumen privat lokal (cache sementara sebelum upload
        // ke Supabase Storage bucket "documents", misal saat proses validasi
        // CV pelamar kerja sebelum dipindahkan permanen).
        'documents_temp' => [
            'driver' => 'local',
            'root' => storage_path('app/private/documents_temp'),
            'throw' => false,
        ],

        // Disk S3-compatible untuk backup database (spatie/laravel-backup),
        // bisa diarahkan ke Supabase Storage S3-compatible endpoint atau AWS S3.
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
            'throw' => false,
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
