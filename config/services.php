<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business API
    |--------------------------------------------------------------------------
    */
    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://graph.facebook.com/v20.0'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'admin_number' => env('WHATSAPP_ADMIN_NUMBER', '6282183999981'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot — notifikasi internal (lead baru, lamaran, gangguan)
    |--------------------------------------------------------------------------
    */
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
        'api_base' => 'https://api.telegram.org/bot',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Maps
    |--------------------------------------------------------------------------
    */
    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'default_lat' => env('GOOGLE_MAPS_DEFAULT_LAT', -5.0667),
        'default_lng' => env('GOOGLE_MAPS_DEFAULT_LNG', 105.5333),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging
    |--------------------------------------------------------------------------
    */
    'fcm' => [
        'project_id' => env('FCM_PROJECT_ID'),
        'service_account_json' => env('FCM_SERVICE_ACCOUNT_JSON', storage_path('app/firebase/service-account.json')),
        'server_key' => env('FCM_SERVER_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Captcha — Cloudflare Turnstile (primer) & Google reCAPTCHA (fallback)
    |--------------------------------------------------------------------------
    */
    'turnstile' => [
        'site_key' => env('CLOUDFLARE_TURNSTILE_SITE_KEY'),
        'secret_key' => env('CLOUDFLARE_TURNSTILE_SECRET_KEY'),
        'verify_url' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'version' => env('RECAPTCHA_VERSION', 'v3'),
        'score_threshold' => (float) env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
        'verify_url' => 'https://www.google.com/recaptcha/api/siteverify',
    ],

    /*
    |--------------------------------------------------------------------------
    | Network Monitoring (NOC API / SNMP gateway)
    |--------------------------------------------------------------------------
    */
    'network_monitor' => [
        'api_url' => env('NETWORK_MONITOR_API_URL'),
        'api_key' => env('NETWORK_MONITOR_API_KEY'),
        'poll_interval' => (int) env('NETWORK_MONITOR_POLL_INTERVAL', 60),
    ],

    'speedtest' => [
        'server_url' => env('SPEEDTEST_SERVER_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Analytics / Tag Manager / Search Console
    |--------------------------------------------------------------------------
    */
    'analytics' => [
        'ga_id' => env('GOOGLE_ANALYTICS_ID'),
        'gtm_id' => env('GOOGLE_TAG_MANAGER_ID'),
        'search_console_verification' => env('GOOGLE_SEARCH_CONSOLE_VERIFICATION'),
    ],
];
