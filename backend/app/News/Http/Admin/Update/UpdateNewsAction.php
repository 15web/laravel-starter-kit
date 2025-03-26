<?php

declare(strict_types=1);

namespace App\News\Http\Admin\Update;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Request\ResolveRouteParameters;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\News\Domain\NewsRepository;
use App\News\Http\Site\Show\ShowNewsRequest;
use App\News\Http\Site\Show\ShowNewsResponse;
use App\User\Authorization\Domain\Role;
use App\User\Authorization\Http\CheckRoleGranted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка обновления новости
 */
#[Router\Middleware('auth')]
final readonly class UpdateNewsAction
{
    public function __construct(
        private NewsRepository $repository,
        private ResolveRouteParameters $resolveRouteParameters,
        private ResolveRequestBody $resolveRequest,
        private ResolveResponse $resolveResponse,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/news/{title}')]
    public function __invoke(): JsonResponse
    {
        Gate::authorize(
            ability: CheckRoleGranted::class,
            arguments: Role::Admin,
        );

        $routeParameters = ($this->resolveRouteParameters)(ShowNewsRequest::class);
        $updateRequest = ($this->resolveRequest)(UpdateNewsRequest::class);

        $news = $this->repository->findByTitle(
            $routeParameters->title,
        );

        if ($news === null) {
            throw ApiException::createNotFoundException('Запись не найдена');
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

        return ($this->resolveResponse)(
            new ApiObjectResponse($response),
        );
    }
}
