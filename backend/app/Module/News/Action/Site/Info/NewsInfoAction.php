<?php

declare(strict_types=1);

namespace App\Module\News\Action\Site\Info;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\News\Model\NewsCollection;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра записи в новостях
 */
#[Router\Middleware('auth')]
final readonly class NewsInfoAction
{
    public function __construct(
        private NewsCollection $newsCollection,
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/news/info')]
    public function __invoke(): JsonResponse
    {
        $newsInfoRequest = ($this->resolveApiRequest)(NewsInfoRequest::class);

        $news = $this->newsCollection->findByTitle($newsInfoRequest->getTitle());
        if ($news === null) {
            /** @var string $message */
            $message = __('news::handler.not_found');

            throw ApiException::createNotFoundException($message, Error::NEWS_NOT_FOUND);
        }

        $newsInfoResponse = new NewsInfoResponse($news);

        return ($this->resolveApiResponse)($newsInfoResponse);
    }
}
