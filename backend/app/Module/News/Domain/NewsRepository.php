<?php

declare(strict_types=1);

namespace App\Module\News\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий новостей
 */
final readonly class NewsRepository
{
    /**
     * @var EntityRepository<News>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $entityManager->getRepository(News::class);
    }

    /**
     * @param positive-int $id
     */
    public function find(int $id): ?News
    {
        return $this->repository->find($id);
    }

    /**
     * @return list<News>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function add(News $news): void
    {
        $this->entityManager->persist($news);
    }

    /**
     * @param non-empty-string $title
     */
    public function existsByTitle(string $title): bool
    {
        $newsCount = $this->repository->count([
            'title' => $title,
        ]);

        return $newsCount > 0;
    }

    /**
     * @param non-empty-string $title
     */
    public function findByTitle(string $title): ?News
    {
        return $this->repository->findOneBy([
            'title' => $title,
        ]);
    }
}
