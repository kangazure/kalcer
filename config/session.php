<?php

return [
    'driver' => env('SESSION_DRIVER', 'redis'),

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => false,

    'encrypt' => env('SESSION_ENCRYPT', true),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION', 'default'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        'jts_session'
    ),

    'path' => '/',

    'domain' => env('SESSION_DOMAIN'),

    // Wajib true di production (HTTPS) — mencegah cookie dikirim via HTTP.
    'secure' => env('SESSION_SECURE_COOKIE', true),

    'http_only' => true,

    // 'lax' cukup aman untuk form login standar dan mencegah CSRF dasar,
    // sekaligus tetap kompatibel dengan navigasi link eksternal.
    'same_site' => 'lax',

    'partitioned' => false,
];
