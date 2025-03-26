<?php

declare(strict_types=1);

namespace App\Blog\Http\Site\Index;

use App\Blog\Domain\PostRepository;
use App\Infrastructure\Request\PaginationRequest;
use App\Infrastructure\Request\ResolveRequestQuery;
use App\Infrastructure\Response\ApiListObjectResponse;
use App\Infrastructure\Response\PaginationResponse;
use App\Infrastructure\Response\ResolveResponse;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка получения списка записей в блоге
 */
final readonly class IndexPostAction
{
    public function __construct(
        private ResolveRequestQuery $resolveQuery,
        private PostRepository $repository,
        private ResolveResponse $resolveResponse,
    ) {}

    #[Router\Get('/blog')]
    public function __invoke(): JsonResponse
    {
        $pagination = ($this->resolveQuery)(PaginationRequest::class);

        $total = $this->repository->countTotal();

        return ($this->resolveResponse)(
            new ApiListObjectResponse(
                data: $this->getPostsData($pagination),
                pagination: new PaginationResponse(
                    total: $total,
                ),
            ),
        );
    }

    /**
     * @return iterable<IndexPostData>
     */
    private function getPostsData(PaginationRequest $pagination): iterable
    {
        $postList = $this->repository->findAll(
            offset: $pagination->offset,
            limit: $pagination->limit,
        );

        foreach ($postList as $post) {
            /** @var positive-int $id */
            $id = $post->getId();

            /** @var non-empty-string $title */
            $title = $post->getTitle();

            /** @var non-empty-string $author */
            $author = $post->getAuthor();

            yield new IndexPostData(
                id: $id,
                title: $title,
                author: $author,
                createdAt: $post->getCreatedAt(),
            );
        }
    }
}
