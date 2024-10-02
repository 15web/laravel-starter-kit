<?php

declare(strict_types=1);

namespace App\Module\News\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class NewsCollection
{
    /**
     * @var EntityRepository<News>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository(News::class);
    }

    public function find(int $id): ?News
    {
        return $this->repository->find($id);
    }

    /**
     * @return News[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function add(News $news): void
    {
        $this->entityManager->persist($news);
    }

    public function isExistsByTitle(string $title): bool
    {
        return (bool) $this->repository->count(['title' => $title]);
    }

    public function findByTitle(string $title): ?News
    {
        return $this->repository->findOneBy(['title' => $title]);
    }
}
