<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Create;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Model\Post;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
final class PostCreateAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {
    }

    #[Get('/blog/create')]
    public function __invoke(): JsonResponse
    {
        $postCreateData = ($this->resolveApiRequest)(PostCreateRequest::class);

        $isPostExist = Post::where('title', $postCreateData->getTitle())->first();

        if ($isPostExist !== null) {
            throw ApiException::createDomainException('Пост с таким заголовком уже существует.', Error::POST_EXISTS);
        }

        $post = new Post();
        $post->create(
            $postCreateData->getTitle(),
            $postCreateData->getAuthor(),
            $postCreateData->getContent(),
        );
        $post->save();

        $postCreateResponseData = new PostCreateResponse($post);

        return ($this->resolveApiResponse)($postCreateResponseData);
    }
}
