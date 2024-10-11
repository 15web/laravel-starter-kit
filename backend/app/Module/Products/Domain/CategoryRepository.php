<?php

declare(strict_types=1);

namespace App\Module\Products\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий категорий товаров
 */
final readonly class CategoryRepository
{
    /**
     * @var EntityRepository<Category>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Category::class);
    }

    public function add(Category $category): void
    {
        $this->entityManager->persist($category);
    }

    /**
     * @param positive-int $id
     */
    public function find(int $id): ?Category
    {
        return $this->repository->find($id);
    }

    /**
     * @param non-empty-string $title
     * @param positive-int|null $parentId
     */
    public function isExistsByTitle(string $title, ?int $parentId = null): bool
    {
        $count = $this->repository->count([
            'title' => $title,
            'parent' => $parentId,
        ]);

        return $count > 0;
    }
}
