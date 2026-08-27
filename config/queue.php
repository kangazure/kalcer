<?php

return [
    'default' => env('QUEUE_CONNECTION', 'redis'),

    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
            'after_commit' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Named Queues — pemisahan beban kerja per jenis job
    |--------------------------------------------------------------------------
    | - emails       : pengiriman email (kontak, lamaran, notifikasi)
    | - notifications: WhatsApp/Telegram/FCM push
    | - reports      : agregasi analytics, backup
    | - default      : job umum lainnya
    */
    'names' => [
        'default' => 'jts_default',
        'emails' => 'jts_emails',
        'notifications' => 'jts_notifications',
        'reports' => 'jts_reports',
    ],

    'batching' => [
        'database' => env('DB_CONNECTION', 'pgsql'),
        'table' => 'job_batches',
    ],

    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database-uuids'),
        'database' => env('DB_CONNECTION', 'pgsql'),
        'table' => 'failed_jobs',
    ],
];
