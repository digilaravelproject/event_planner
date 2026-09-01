<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $staff, public string $plainPassword) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your EventPlanner staff account');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.staff-welcome');
    }
}
