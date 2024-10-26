<?php

declare(strict_types=1);

namespace App\Products\Http\Category\Index;

use App\Infrastructure\Response\ApiListObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\Products\Domain\Category;
use Doctrine\ORM\EntityManager;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка вывода "дерева" категорий товаров
 */
final readonly class IndexCategoryAction
{
    /**
     * @var NestedTreeRepository<Category>
     */
    private NestedTreeRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
        private ResolveResponse $resolveResponse,
    ) {
        /** @var NestedTreeRepository<Category> $repository */
        $repository = $this->entityManager->getRepository(Category::class);

        $this->repository = $repository;
    }

    #[Router\Get('/products/category')]
    public function __invoke(): JsonResponse
    {
        /** @var iterable<Category> $categories */
        $categories = $this->repository->getRootNodesQuery()->toIterable();

        return ($this->resolveResponse)(
            new ApiListObjectResponse(
                data: $this->getTreeData($categories),
            ),
        );
    }

    /**
     * @param iterable<Category> $categories
     *
     * @return iterable<IndexCategoryData>
     */
    private function getTreeData(iterable $categories): iterable
    {
        foreach ($categories as $category) {
            /** @var iterable<Category> $children */
            $children = $this->repository->getChildrenQuery($category)->toIterable();

            yield new IndexCategoryData(
                id: $category->getId(),
                title: $category->getTitle(),
                children: $this->getTreeData($children),
                createdAt: $category->getCreatedAt(),
                updatedAt: $category->getUpdatedAt(),
            );
        }
    }
}
