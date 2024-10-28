<?php

declare(strict_types=1);

namespace App\News\Notification;

use App\News\Http\Site\Subscribe\SubscribeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Письмо с запросом на подписку
 */
final class Subscribe extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private SubscribeRequest $subscribeRequest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Подписка на новости',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.news.subscribe',
            with: [
                'subscribeRequest' => $this->subscribeRequest,
            ],
        );
    }
}
