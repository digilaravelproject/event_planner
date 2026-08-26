<?php

namespace App\Mail;

use App\Models\VendorAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminVendorRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public VendorAccount $vendor) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New vendor registered: '.$this->vendor->business_name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-vendor-registered');
    }
}
