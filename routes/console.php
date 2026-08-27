<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Scheduler — Jadwal Tugas Otomatis JTS
|--------------------------------------------------------------------------
| Dieksekusi oleh single cron entry di server Ubuntu:
|   * * * * * php /var/www/ptjts/artisan schedule:run >> /dev/null 2>&1
*/

Schedule::command('jts:poll-network')
    ->everyMinute()
    ->runInBackground()
    ->withoutOverlapping(expiresAt: 2)
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::channel('network_monitor')->error('Scheduler jts:poll-network gagal dieksekusi.');
    });

Schedule::command('jts:aggregate-analytics')
    ->dailyAt('00:15')
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler-analytics.log'));

Schedule::command('jts:generate-sitemap')
    ->dailyAt('02:00')
    ->runInBackground()
    ->withoutOverlapping();

Schedule::command('jts:clean-visitor-logs --no-interaction --days=90')
    ->weekly()
    ->sundays()
    ->at('03:00');

Schedule::command('backup:run --only-db')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/backup.log'));

Schedule::command('backup:clean')
    ->dailyAt('01:30')
    ->withoutOverlapping();

Schedule::command('horizon:snapshot')->everyFiveMinutes();

Schedule::command('queue:prune-batches --hours=48')->daily();
