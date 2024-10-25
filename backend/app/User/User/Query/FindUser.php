<?php

declare(strict_types=1);

namespace App\User\User\Query;

use App\User\Authentication\Domain\AuthToken;
use App\User\User\Domain\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Возвращает данные пользователя
 */
final readonly class FindUser
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entityManager,
    ) {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function __invoke(FindUserQuery $query): ?User
    {
        $queryBuilder = $this->repository->createQueryBuilder('user');

        if ($query->email !== null) {
            $queryBuilder
                ->where('user.email = :email')
                ->setParameter('email', $query->email->value);
        }

        if ($query->authToken !== null) {
            $authToken = AuthToken::createFromString($query->authToken);

            $queryBuilder
                ->join('user.tokens', 'tokens', Join::WITH, 'tokens.id = :tokenId')
                ->setParameter('tokenId', (string) $authToken->tokenId);
        }

        /** @var User|null $result */
        $result = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}
