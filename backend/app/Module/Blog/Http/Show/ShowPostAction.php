<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Show;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveFromRoute;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Domain\PostRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка просмотра записи в блоге
 */
final readonly class ShowPostAction
{
    public function __construct(
        private PostRepository $repository,
        private ResolveFromRoute $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/blog/{title}')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveApiRequest)(ShowPostRequest::class);

        $post = $this->repository->findByTitle($request->title);
        if ($post === null) {
            throw ApiException::createNotFoundException('Запись не найдена', Error::NEWS_NOT_FOUND);
        }

        /** @var positive-int $id */
        $id = $post->getId();

        /** @var non-empty-string $title */
        $title = $post->getTitle();

        /** @var non-empty-string $author */
        $author = $post->getAuthor();

        /** @var non-empty-string $content */
        $content = $post->getContent();

        $response = new ShowPostResponse(
            id: $id,
            title: $title,
            author: $author,
            content: $content,
            createdAt: $post->getCreatedAt(),
            updatedAt: $post->getUpdatedAt(),
        );

        return ($this->resolveApiResponse)($response);
    }
}
