<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Subscribe;

use App\Infrastructure\Request\ResolveFromRequest;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\Module\News\Notification\Subscribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка для подписки на новости
 *
 * @todo тест на 400 (не передали емейл, передали невалидный емейл)
 */
final readonly class SubscribeAction
{
    public function __construct(
        private ResolveFromRequest $resolveApiRequest,
        private ResolveSuccessResponse $successResponse,
    ) {}

    #[Router\Post('/news/subscribe')]
    public function __invoke(): JsonResponse
    {
        $subscribeRequest = ($this->resolveApiRequest)(SubscribeRequest::class);
        $mail = new Subscribe($subscribeRequest);

        Mail::to($subscribeRequest->email)->send($mail);

        return ($this->successResponse)();
    }
}
