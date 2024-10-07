<?php

declare(strict_types=1);

namespace App\Module\News\Notification;

use App\Module\News\Action\Subscribe\SubscribeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Письмо с запросом на подписку
 */
final class Subscribe extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(private SubscribeRequest $subscribeRequest) {}

    public function build(): self
    {
        return $this->view('emails.subscribe')
            ->with(['subscribeRequest' => $this->subscribeRequest]);
    }
}
