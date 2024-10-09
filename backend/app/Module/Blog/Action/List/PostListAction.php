<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\List;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Model\Post;
use Illuminate\Http\JsonResponse;
use Iterator;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка получения списка записей в блоге
 */
final readonly class PostListAction
{
    public function __construct(
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/blog/list')]
    public function __invoke(): JsonResponse
    {
        return ($this->resolveApiResponse)($this->getPostsData());
    }

    private function getPostsData(): Iterator
    {
        foreach (Post::query()->get() as $post) {
            yield new PostListData($post->id, $post->title, $post->author, $post->created_at);
        }
    }
}
