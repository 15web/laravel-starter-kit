<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Info;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\NewsRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра записи в новостях
 */
#[Router\Middleware('auth')]
final readonly class NewsInfoAction
{
    public function __construct(
        private NewsRepository $newsCollection,
        private ResolveRequest $resolveApiRequest,
        private ResolveResponse $resolveApiResponse,
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
