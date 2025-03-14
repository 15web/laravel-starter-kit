<?php

declare(strict_types=1);

namespace App\User\User\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

/**
 * Репозиторий пользователей
 */
final readonly class UserRepository
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(
        EntityManager $entityManager,
    ) {
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function find(Uuid $id): ?User
    {
        return $this->repository->find($id);
    }
}
