<?php

namespace App\Listeners;

use App\Events\NewJobApplicationSubmitted;
use App\Mail\NewJobApplicationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewApplication implements ShouldQueue
{
    public string $queue = 'jts_notifications';

    public function handle(NewJobApplicationSubmitted $event): void
    {
        $application = $event->application->loadMissing('career');

        Mail::to(config('mail.admin_notification'))
            ->queue(new NewJobApplicationMail($application));

        $recipients = User::query()
            ->whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'admin']))
            ->where('status', 'active')
            ->get();

        foreach ($recipients as $recipient) {
            Notification::query()->create([
                'user_id' => $recipient->id,
                'channel' => 'database',
                'type' => 'new_application',
                'title' => 'Lamaran Kerja Baru',
                'body' => "{$application->full_name} melamar untuk posisi {$application->career?->title}",
                'action_url' => route('admin.career.applications', $application->career_id),
                'metadata' => ['application_id' => $application->id],
            ]);
        }
    }
}
