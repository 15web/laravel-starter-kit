<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Store;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\ErrorCode;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\News\Domain\News;
use App\Module\News\Domain\NewsRepository;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\Authorization\Http\CheckRoleGranted;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания новости
 */
#[Router\Middleware('auth')]
final readonly class StoreNewsAction
{
    public function __construct(
        private NewsRepository $repository,
        private Flusher $flusher,
        private ResolveRequestBody $resolveRequest,
        private ResolveResponse $resolveResponse,
    ) {}

    #[Router\Post('/news')]
    public function __invoke(): JsonResponse
    {
        Gate::authorize(CheckRoleGranted::class, Role::User);

        $request = ($this->resolveRequest)(StoreNewsRequest::class);

        $newsExists = $this->repository->existsByTitle($request->title);
        if ($newsExists) {
            throw ApiException::createDomainException('Новость с таким заголовком уже существует', ErrorCode::EXISTS);
        }

        $news = new News($request->title);
        $this->repository->add($news);

        $this->flusher->flush();

        /** @var positive-int $id */
        $id = $news->getId();

        /** @var non-empty-string $title */
        $title = $news->getTitle();

        $response = new StoreNewsResponse(
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
