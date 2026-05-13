<?php

namespace App\Mail;

use App\Models\Spot;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RestaurantRecommended extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Spot $spot, public string $claimUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your restaurant was recommended on BenLocal!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.restaurant-recommended',
        );
    }
}
