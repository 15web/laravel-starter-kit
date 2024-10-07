<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DomainException;

/**
 * Репозиторий токенов авторизации
 */
final readonly class Tokens
{
    /**
     * @var EntityRepository<Token>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManager $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(Token::class);
    }

    public function get(string $id): Token
    {
        $token = $this->repository->find($id);
        if ($token === null) {
            /** @var string $message */
            $message = __('user::token.not_found');

            throw new DomainException($message);
        }

        return $token;
    }
}
