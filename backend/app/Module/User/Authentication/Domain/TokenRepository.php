<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Domain;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий токенов авторизации
 */
final readonly class TokenRepository
{
    /**
     * @var EntityRepository<Token>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(Token::class);
    }

    public function find(string $id): ?Token
    {
        return $this->repository->find($id);
    }
}
