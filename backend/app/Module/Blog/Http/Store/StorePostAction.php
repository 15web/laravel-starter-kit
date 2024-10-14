<?php

declare(strict_types=1);

namespace App\Module\Blog\Http\Store;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Infrastructure\Doctrine\Flusher;
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
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
        private PostRepository $repository,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/blog')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveApiRequest)(StorePostRequest::class);
        //        dump($request, $r->all());
        $isPostExists = $this->repository->isExistsByTitle($request->title);
        if ($isPostExists) {
            throw ApiException::createDomainException('Запись с таким заголовком уже существует', Error::EXISTS);
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

        return ($this->resolveApiResponse)($response);
    }
}
