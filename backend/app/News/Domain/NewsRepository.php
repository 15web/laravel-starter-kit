<?php

declare(strict_types=1);

namespace App\News\Domain;

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
     * @param non-negative-int $offset
     * @param positive-int $limit
     *
     * @return list<News>
     */
    public function findAll(int $offset, int $limit): array
    {
        $query = $this->repository
            ->createQueryBuilder('news')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        /** @var list<News> $result */
        $result = $query->getResult();

        return $result;
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

    /**
     * @return non-negative-int
     */
    public function countTotal(): int
    {
        return $this->repository->count();
    }
}
