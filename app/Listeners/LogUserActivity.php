<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserActivity implements ShouldQueue
{
    public function handle(Login $event): void
    {
        ActivityLog::query()->create([
            'user_id' => $event->user->getKey(),
            'log_name' => 'auth',
            'description' => "Pengguna {$event->user->name} berhasil login.",
            'subject_type' => 'User',
            'subject_id' => $event->user->getKey(),
            'event' => 'login',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
