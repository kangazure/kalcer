<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    | Hanya domain resmi JTS dan tools internal yang diizinkan mengakses REST
    | API. JANGAN gunakan wildcard '*' di production untuk endpoint yang
    | memerlukan kredensial (cookie/token).
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'webhooks/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => array_filter(explode(',', (string) env(
        'CORS_ALLOWED_ORIGINS',
        'https://rikoardianto.web.id,https://www.rikoardianto.web.id'
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
    ],

    'exposed_headers' => ['X-RateLimit-Remaining', 'X-RateLimit-Limit'],

    'max_age' => 86400,

    'supports_credentials' => true,
];
