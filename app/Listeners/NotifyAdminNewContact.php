<?php

namespace App\Listeners;

use App\Events\NewContactSubmitted;
use App\Mail\NewContactNotificationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Mengirim notifikasi email + in-app notification ke staff yang relevan
 * (Admin, Marketing, Operator) saat ada pesan kontak baru masuk.
 */
class NotifyAdminNewContact implements ShouldQueue
{
    public string $queue = 'jts_notifications';

    public function handle(NewContactSubmitted $event): void
    {
        $contact = $event->contact;

        Mail::to(config('mail.admin_notification'))
            ->queue(new NewContactNotificationMail($contact));

        $recipients = User::query()
            ->whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'admin', 'marketing']))
            ->where('status', 'active')
            ->get();

        foreach ($recipients as $recipient) {
            Notification::query()->create([
                'user_id' => $recipient->id,
                'channel' => 'database',
                'type' => 'new_contact',
                'title' => 'Pesan Kontak Baru',
                'body' => "{$contact->name} mengirim pesan: \"{$contact->subject}\"",
                'action_url' => route('admin.contact.show', $contact->id),
                'metadata' => [
                    'contact_id' => $contact->id,
                    'source' => $contact->source,
                ],
            ]);
        }
    }
}
