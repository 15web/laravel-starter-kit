<?php

declare(strict_types=1);

namespace App\Module\Blog\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий записей блога
 */
final readonly class PostRepository
{
    /**
     * @var EntityRepository<Post>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Post::class);
    }

    public function add(Post $post): void
    {
        $this->entityManager->persist($post);
    }

    /**
     * @param positive-int $id
     */
    public function find(int $id): ?Post
    {
        return $this->repository->find($id);
    }

    public function findByTitle(string $title): ?Post
    {
        return $this->repository->findOneBy(['title' => $title]);
    }

    /**
     * @param non-negative-int $offset
     * @param positive-int $limit
     *
     * @return list<Post>
     */
    public function findAll(int $offset, int $limit): array
    {
        $query = $this->repository
            ->createQueryBuilder('post')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        /** @var list<Post> $result */
        $result = $query->getResult();

        return $result;
    }

    /**
     * @param non-empty-string $title
     */
    public function existsByTitle(string $title): bool
    {
        $count = $this->repository->count(['title' => $title]);

        return $count > 0;
    }

    /**
     * @return non-negative-int
     */
    public function countTotal(): int
    {
        return $this->repository->count();
    }
}
