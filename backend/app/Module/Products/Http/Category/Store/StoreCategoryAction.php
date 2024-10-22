<?php

declare(strict_types=1);

namespace App\Module\Products\Http\Category\Store;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
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
        private ResolveRequestBody $resolveRequest,
        private ResolveResponse $resolveResponse,
        private CategoryRepository $repository,
        private Flusher $flusher,
    ) {}

    #[Router\Post('/products/category')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveRequest)(StoreCategoryRequest::class);

        $categoryExists = $this->repository->existsByTitle($request->title, $request->parent);
        if ($categoryExists) {
            throw ApiException::createDomainException('Категория с таким заголовком уже существует', Error::EXISTS);
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

        return ($this->resolveResponse)(
            new ApiObjectResponse($response),
        );
    }
}
