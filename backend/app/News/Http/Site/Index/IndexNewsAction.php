<?php

declare(strict_types=1);

namespace App\News\Http\Site\Index;

use App\Infrastructure\Request\PaginationRequest;
use App\Infrastructure\Request\ResolveRequestQuery;
use App\Infrastructure\Response\ApiListObjectResponse;
use App\Infrastructure\Response\PaginationResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\News\Domain\NewsRepository;
use App\User\Authorization\Domain\Role;
use App\User\Authorization\Http\CheckRoleGranted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра новостей
 */
#[Router\Middleware('auth')]
final readonly class IndexNewsAction
{
    public function __construct(
        private ResolveRequestQuery $resolveQuery,
        private NewsRepository $repository,
        private ResolveResponse $resolveResponse,
    ) {}

    #[Router\Get('/news')]
    public function __invoke(): JsonResponse
    {
        Gate::authorize(CheckRoleGranted::class, Role::User);

        $pagination = ($this->resolveQuery)(PaginationRequest::class);

        $total = $this->repository->countTotal();

        return ($this->resolveResponse)(
            new ApiListObjectResponse(
                data: $this->getNewsData($pagination),
                pagination: new PaginationResponse(
                    total: $total,
                ),
            ),
        );
    }

    /**
     * @return iterable<IndexNewsData>
     */
    private function getNewsData(PaginationRequest $pagination): iterable
    {
        $newsList = $this->repository->findAll(
            offset: $pagination->offset,
            limit: $pagination->limit,
        );

        foreach ($newsList as $news) {
            /** @var positive-int $id */
            $id = $news->getId();

            /** @var non-empty-string $title */
            $title = $news->getTitle();

            yield new IndexNewsData(
                id: $id,
                title: $title,
                createdAt: $news->getCreatedAt(),
            );
        }
    }
}
