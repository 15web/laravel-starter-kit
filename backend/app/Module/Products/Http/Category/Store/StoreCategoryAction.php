<?php

declare(strict_types=1);

namespace App\Module\Products\Http\Category\Store;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\Products\Domain\Category;
use App\Module\Products\Domain\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка создания категории товаров
 */
final readonly class StoreCategoryAction
{
    public function __construct(
        private ResolveApiRequest $resolveApiRequest,
        private ResolveApiResponse $resolveApiResponse,
        private CategoryRepository $repository,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/products/category')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveApiRequest)(StoreCategoryRequest::class);

        $isCategoryExists = $this->repository->isExistsByTitle($request->title, $request->parent);
        if ($isCategoryExists) {
            throw ApiException::createDomainException('Категория с таким заголовком уже существует', Error::NEWS_EXISTS);
        }

        $parent = null;
        if ($request->parent !== null) {
            $parent = $this->repository->find($request->parent);

            if ($parent === null) {
                throw ApiException::createDomainException('Не найдена родительская категория', Error::BAD_REQUEST);
            }
        }

        $category = new Category(
            title: $request->title,
            parent: $parent,
        );

        $this->repository->add($category);
        $this->flusher->flush();

        /** @var positive-int $id */
        $id = $category->getId();

        /** @var non-empty-string $title */
        $title = $category->getTitle();

        $response = new StoreCategoryResponse(
            id: $id,
            title: $title,
            createdAt: $category->getCreatedAt(),
            updatedAt: $category->getUpdatedAt(),
        );

        return ($this->resolveApiResponse)($response);
    }
}
