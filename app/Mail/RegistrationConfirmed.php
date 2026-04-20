<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public Registration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $event = $this->registration->event;

        return $this->subject("Votre pass pour {$event->title}")
            ->view('emails.registration_confirmed')
            ->with([
                'registration' => $this->registration,
                'event' => $event,
                'user' => $this->registration->user,
            ]);
    }
}
