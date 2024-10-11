<?php

declare(strict_types=1);

namespace App\Module\Products\Http\Category\Index;

use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Module\Products\Domain\Category;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка вывода "дерева" категорий товаров
 */
final readonly class IndexCategoryAction
{
    public function __construct(
        private EntityManager $entityManager,
        private ResolveApiResponse $resolveApiResponse,
    ) {}

    #[Router\Get('/products/category')]
    public function __invoke(): JsonResponse
    {
        $repository = $this->entityManager->getRepository(Category::class);

        /** @var QueryBuilder $query */
        $query = $repository->getRootNodesQueryBuilder();

        /** @var iterable<Category> $categories */
        $categories = $query->getQuery()->toIterable();

        return ($this->resolveApiResponse)($this->getTreeData($categories));
    }

    /**
     * @param iterable<Category> $categories
     *
     * @return iterable<IndexCategoryResponse>
     */
    private function getTreeData(iterable $categories): iterable
    {
        foreach ($categories as $category) {
            yield new IndexCategoryResponse(
                id: $category->getId(),
                title: $category->getTitle(),
                children: $this->getTreeData($category->getChildren()),
                createdAt: $category->getCreatedAt(),
                updatedAt: $category->getUpdatedAt(),
            );
        }
    }
}
