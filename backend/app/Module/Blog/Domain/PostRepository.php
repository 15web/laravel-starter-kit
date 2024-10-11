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
     * @return list<Post>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param non-empty-string $title
     */
    public function isExistsByTitle(string $title): bool
    {
        $count = $this->repository->count(['title' => $title]);

        return $count > 0;
    }
}
