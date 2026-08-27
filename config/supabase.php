<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Project Configuration
    |--------------------------------------------------------------------------
    | Konfigurasi koneksi ke layer Supabase di luar database (Auth, Storage,
    | Realtime, Edge Functions). Diakses melalui App\Services\SupabaseClient.
    */
    'url' => env('SUPABASE_URL'),
    'anon_key' => env('SUPABASE_ANON_KEY'),
    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),
    'jwt_secret' => env('SUPABASE_JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Storage Buckets
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'buckets' => [
            'media' => env('SUPABASE_STORAGE_BUCKET_MEDIA', 'media'),
            'gallery' => env('SUPABASE_STORAGE_BUCKET_GALLERY', 'gallery'),
            'documents' => env('SUPABASE_STORAGE_BUCKET_DOCUMENTS', 'documents'),
            'avatars' => env('SUPABASE_STORAGE_BUCKET_AVATARS', 'avatars'),
        ],
        'signed_url_ttl' => 3600, // detik, untuk dokumen privat (CV pelamar)
    ],

    /*
    |--------------------------------------------------------------------------
    | Realtime
    |--------------------------------------------------------------------------
    */
    'realtime' => [
        'enabled' => env('SUPABASE_REALTIME_ENABLED', true),
        'channels' => [
            'network-monitor' => 'public:network_monitor',
            'trouble-report' => 'public:trouble_report',
            'announcement' => 'public:announcement',
            'notification' => 'public:notification',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Edge Functions Base Path
    |--------------------------------------------------------------------------
    */
    'edge_functions' => [
        'base_url' => env('SUPABASE_URL') ? rtrim(env('SUPABASE_URL'), '/').'/functions/v1' : null,
        'functions' => [
            'send-notification' => 'send-notification',
            'process-coverage-check' => 'process-coverage-check',
            'aggregate-analytics' => 'aggregate-analytics',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Defaults
    |--------------------------------------------------------------------------
    */
    'http' => [
        'timeout' => 15,
        'retry_times' => 2,
        'retry_sleep_ms' => 200,
    ],
];
