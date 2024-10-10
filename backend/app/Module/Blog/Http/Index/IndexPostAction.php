<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Index;

use App\Infrastructure\Response\ResolveResponse;
use App\Module\Blog\Domain\PostRepository;
use Illuminate\Http\JsonResponse;
use Iterator;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка получения списка записей в блоге
 */
final readonly class IndexPostAction
{
    public function __construct(
        private PostRepository $repository,
        private ResolveResponse $resolveResponse,
    ) {}

    #[Router\Get('/blog')]
    public function __invoke(): JsonResponse
    {
        return ($this->resolveResponse)($this->getPostsData());
    }

    private function getPostsData(): Iterator
    {
        $postList = $this->repository->findAll();
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
