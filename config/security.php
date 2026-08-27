<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Presets
    |--------------------------------------------------------------------------
    | Dipakai oleh App\Providers\RouteServiceProvider untuk mendefinisikan
    | RateLimiter::for(...) dan oleh middleware throttle pada route groups.
    */
    'throttle' => [
        'api' => (int) env('THROTTLE_API_PER_MINUTE', 60),
        'login' => (int) env('THROTTLE_LOGIN_PER_MINUTE', 5),
        'contact_form' => (int) env('THROTTLE_CONTACT_PER_MINUTE', 3),
        'comment' => (int) env('THROTTLE_COMMENT_PER_MINUTE', 5),
        'coverage_check' => 10,
        'job_application' => 3,
        'newsletter' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers (App\Http\Middleware\SecurityHeaders)
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'geolocation=(self), microphone=(), camera=()',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains; preload',
        'content_security_policy' => implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://challenges.cloudflare.com https://www.google.com https://www.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: blob: https://*.supabase.co https://www.google-analytics.com",
            "connect-src 'self' https://*.supabase.co wss://*.supabase.co https://www.google-analytics.com",
            "frame-src 'self' https://challenges.cloudflare.com https://www.google.com https://www.google.com/maps/",
            "object-src 'none'",
            "base-uri 'self'",
        ]),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Validation
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'image' => [
            'max_size_kb' => 10240,
            'mimes' => ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'],
        ],
        'document' => [
            'max_size_kb' => 5120,
            'mimes' => ['pdf', 'doc', 'docx'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Login Lockout
    |--------------------------------------------------------------------------
    */
    'lockout' => [
        'max_attempts' => 5,
        'decay_minutes' => 15,
    ],
];
