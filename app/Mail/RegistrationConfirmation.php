<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RegistrationConfirmation extends Mailable
{
    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmare Înregistrare - '.config('simpozion.event_name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.registration-confirmation',
        );
    }
}
