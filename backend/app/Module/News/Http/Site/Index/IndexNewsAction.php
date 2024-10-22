<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Index;

use App\Infrastructure\Response\ApiListObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\NewsRepository;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\Authorization\Http\CheckRoleGranted;
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
        private NewsRepository $repository,
        private ResolveResponse $resolveResponse,
    ) {}

    #[Router\Get('/news')]
    public function __invoke(): JsonResponse
    {
        Gate::authorize(CheckRoleGranted::class, Role::User);

        return ($this->resolveResponse)(
            new ApiListObjectResponse($this->getNewsData()),
        );
    }

    /**
     * @return iterable<IndexNewsData>
     */
    private function getNewsData(): iterable
    {
        $newsList = $this->repository->findAll();

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
