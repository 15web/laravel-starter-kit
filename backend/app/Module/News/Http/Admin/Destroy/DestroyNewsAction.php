<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Destroy;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRouteParameters;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\Module\News\Domain\NewsRepository;
use App\Module\News\Http\Site\Show\ShowNewsRequest;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\Authorization\Http\CheckRoleGranted;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка удаления новости
 */
#[Router\Middleware('auth')]
final readonly class DestroyNewsAction
{
    public function __construct(
        private NewsRepository $repository,
        private ResolveRouteParameters $resolveRouteParameters,
        private ResolveSuccessResponse $resolveResponse,
        private EntityManager $entityManager,
        private Flusher $flusher,
    ) {}

    #[Router\Delete('/news/{title}')]
    public function __invoke(): JsonResponse
    {
        Gate::authorize(CheckRoleGranted::class, Role::User);

        $routeParameters = ($this->resolveRouteParameters)(ShowNewsRequest::class);

        $news = $this->repository->findByTitle(
            $routeParameters->title
        );

        if ($news === null) {
            throw ApiException::createNotFoundException('Запись не найдена', Error::NOT_FOUND);
        }

        $this->entityManager->remove($news);
        $this->flusher->flush();

        return ($this->resolveResponse)();
    }
}
