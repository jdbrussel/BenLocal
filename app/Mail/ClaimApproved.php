<?php

namespace App\Mail;

use App\Models\SpotClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaimApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SpotClaim $claim) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Claim Approved - ' . $this->claim->spot->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.claims.approved',
        );
    }
}
