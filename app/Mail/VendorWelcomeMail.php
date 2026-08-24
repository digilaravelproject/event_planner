<?php

namespace App\Mail;

use App\Models\VendorAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public VendorAccount $vendor) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to the Shaadi Sense Vendor Network');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.vendor-welcome');
    }
}
