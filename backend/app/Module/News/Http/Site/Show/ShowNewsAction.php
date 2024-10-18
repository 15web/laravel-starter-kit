<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Show;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\Request\ResolveRouteParameters;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\NewsRepository;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\Authorization\Http\CheckRoleGranted;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра новости
 */
#[Router\Middleware('auth')]
final readonly class ShowNewsAction
{
    public function __construct(
        private NewsRepository $repository,
        private ResolveRouteParameters $resolveRequest,
        private ResolveResponse $resolveResponse,
        private CheckRoleGranted $denyUnlessUserHasRole,
    ) {}

    #[Router\Get('/news/{title}')]
    public function __invoke(): JsonResponse
    {
        ($this->denyUnlessUserHasRole)(Role::User);

        $request = ($this->resolveRequest)(ShowNewsRequest::class);

        $news = $this->repository->findByTitle(
            $request->title
        );

        if ($news === null) {
            throw ApiException::createNotFoundException('Запись не найдена', Error::NOT_FOUND);
        }

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
