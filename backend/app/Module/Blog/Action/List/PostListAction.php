<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\List;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Model\Post;
use Illuminate\Http\JsonResponse;
use Iterator;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
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

    /**
     * @return Iterator
     */
    private function getPostsData(): iterable
    {
        foreach (Post::all() as $post) {
            yield new PostListData($post->id, $post->title, $post->author, $post->created_at);
        }
    }
}
