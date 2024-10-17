<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Subscribe;

use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\Module\News\Notification\Subscribe;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка для подписки на новости
 */
final readonly class SubscribeAction
{
    public function __construct(
        private ResolveRequest $resolveRequest,
        private ResolveSuccessResponse $successResponse,
    ) {}

    #[Router\Post('/subscribe')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveRequest)(SubscribeRequest::class);
        $mail = new Subscribe($request);

        Mail::to($request->email)->send($mail);

        return ($this->successResponse)();
    }
}
