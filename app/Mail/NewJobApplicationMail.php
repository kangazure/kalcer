<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lamaran Baru — '.($this->application->career?->title ?? 'Posisi Tidak Diketahui'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.career.new-application',
            with: ['application' => $this->application],
        );
    }
}
