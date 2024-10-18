<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Update;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Request\ResolveRouteParameters;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\NewsRepository;
use App\Module\News\Http\Site\Show\ShowNewsRequest;
use App\Module\News\Http\Site\Show\ShowNewsResponse;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\Authorization\Http\IsGranted;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка обновления новости
 */
#[Router\Middleware('auth')]
#[IsGranted(Role::User)]
final readonly class UpdateNewsAction
{
    public function __construct(
        private NewsRepository $repository,
        private ResolveRouteParameters $resolveRouteParameters,
        private ResolveRequest $resolveRequest,
        private ResolveResponse $resolveResponse,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/news/{title}')]
    public function __invoke(): JsonResponse
    {
        $routeParameters = ($this->resolveRouteParameters)(ShowNewsRequest::class);
        $updateRequest = ($this->resolveRequest)(UpdateNewsRequest::class);

        $news = $this->repository->findByTitle(
            $routeParameters->title
        );

        if ($news === null) {
            throw ApiException::createNotFoundException('Запись не найдена', Error::NOT_FOUND);
        }

        $news->setTitle($updateRequest->title);
        $this->flusher->flush();

        /** @var positive-int $id */
        $id = $news->getId();

        /** @var non-empty-string $title */
        $title = $news->getTitle();

        $response = new ShowNewsResponse(
            id: $id,
            title: $title,
            createdAt: $news->getCreatedAt(),
            updatedAt: $news->getUpdatedAt(),
        );

        return ($this->resolveResponse)($response);
    }
}
