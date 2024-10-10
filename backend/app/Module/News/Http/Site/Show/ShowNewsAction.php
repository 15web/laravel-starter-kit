<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Show;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Request\ResolveFromRoute;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\NewsRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра записи в новостях
 *
 * @todo тест на ручку (200, 404)
 */
final readonly class ShowNewsAction
{
    public function __construct(
        private NewsRepository $newsCollection,
        private ResolveFromRoute $resolveRequest,
        private ResolveResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/news/{title}')]
    public function __invoke(): JsonResponse
    {
        $newsInfoRequest = ($this->resolveRequest)(ShowNewsRequest::class);

        $news = $this->newsCollection->findByTitle(
            $newsInfoRequest->title
        );

        if ($news === null) {
            throw ApiException::createNotFoundException('Запись не найдена', Error::NEWS_NOT_FOUND);
        }

        $newsInfoResponse = new ShowNewsResponse(
            id: $news->getId(),
            title: $news->getTitle(),
            createdAt: $news->getCreatedAt(),
        );

        return ($this->resolveApiResponse)($newsInfoResponse);
    }
}
