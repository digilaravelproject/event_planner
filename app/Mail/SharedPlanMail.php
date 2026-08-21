<?php

namespace App\Mail;

use App\Models\UserEventPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SharedPlanMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UserEventPlan $plan, private string $pdfContent) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Shaadi Sense plan: '.$this->plan->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.shared-plan');
    }

    public function attachments(): array
    {
        return [Attachment::fromData(
            fn (): string => $this->pdfContent,
            'wedding-plan-'.$this->plan->id.'.pdf'
        )->withMime('application/pdf')];
    }
}
