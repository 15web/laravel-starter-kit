<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Uuid;

/**
 * Репозиторий токенов авторизации
 */
final readonly class UserTokenRepository
{
    /**
     * @var EntityRepository<UserToken>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(UserToken::class);
    }

    public function find(Uuid $id): ?UserToken
    {
        return $this->repository->find(
            (string) $id,
        );
    }
}
