<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class Tokens
{
    /**
     * @var EntityRepository<Token>
     */
    private EntityRepository $repository;

    public function __construct(private readonly EntityManager $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Token::class);
    }

    public function get(string $id): Token
    {
        $token = $this->repository->find($id);
        if ($token === null) {
            throw new \DomainException('Токен не найден');
        }

        return $token;
    }
}
