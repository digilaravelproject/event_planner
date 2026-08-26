<?php

namespace App\Mail;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UserSubscription $subscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Shaadi Sense subscription has ended');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscription-expired');
    }
}
