<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Store;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\ErrorCode;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\Blog\Domain\Post;
use App\Module\Blog\Domain\PostRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания записи в блоге
 */
final readonly class StorePostAction
{
    public function __construct(
        private ResolveRequestBody $resolveRequest,
        private ResolveResponse $resolveResponse,
        private PostRepository $repository,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/blog')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveRequest)(StorePostRequest::class);

        $postExists = $this->repository->existsByTitle($request->title);
        if ($postExists) {
            throw ApiException::createDomainException('Запись с таким заголовком уже существует', ErrorCode::EXISTS);
        }
        $post = new Post(
            title: $request->title,
            author: $request->author,
            content: $request->content,
        );

        $this->repository->add($post);
        $this->flusher->flush();

        /** @var positive-int $id */
        $id = $post->getId();

        /** @var non-empty-string $title */
        $title = $post->getTitle();
        $response = new StorePostResponse(
            id: $id,
            title: $title,
            createdAt: $post->getCreatedAt(),
            updatedAt: $post->getUpdatedAt(),
        );

        return ($this->resolveResponse)(
            new ApiObjectResponse($response),
        );
    }
}
