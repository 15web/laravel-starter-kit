<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Create;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Blog\Model\Post;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания записи в блоге
 */
final readonly class PostCreateAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Post('/blog/create')]
    public function __invoke(): JsonResponse
    {
        $postCreateData = ($this->resolveApiRequest)(PostCreateRequest::class);

        $isPostExist = Post::query()->where('title', $postCreateData->getTitle())->first();

        if ($isPostExist !== null) {
            /** @var string $message */
            $message = __('blog::handler.exists');

            throw ApiException::createDomainException($message, Error::POST_EXISTS);
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
