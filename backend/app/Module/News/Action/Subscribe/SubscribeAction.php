<?php

declare(strict_types=1);

namespace App\Module\News\Action\Subscribe;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\News\Notification\Subscribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
final class SubscribeAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Post('/news/subscribe')]
    public function __invoke(): JsonResponse
    {
        $subscribeRequest = ($this->resolveApiRequest)(SubscribeRequest::class);

        $mail = new Subscribe($subscribeRequest);
        $mail->subject('Подписка на новости');
        $mail->to($subscribeRequest->getEmail());

        Mail::queue($mail); // отправка через очередь

        return ($this->resolveApiResponse)(['success' => true]);
    }
}
