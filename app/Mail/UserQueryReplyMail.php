<?php

namespace App\Mail;

use App\Models\UserQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserQueryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public UserQuery $query) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Reply to your query: '.$this->query->subject); }
    public function content(): Content { return new Content(view: 'mail.user-query-reply'); }
}
