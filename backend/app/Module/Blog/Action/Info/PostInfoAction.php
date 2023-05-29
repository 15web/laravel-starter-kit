<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Info;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Model\Post;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
final class PostInfoAction
{
    public function __construct(
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Router\Get('/blog/info/{post:title}')]
    public function __invoke(Post $post): JsonResponse
    {
        $postInfoResponse = new PostInfoResponse($post);

        return ($this->resolveApiResponse)($postInfoResponse);
    }
}
