<?php

declare(strict_types=1);

namespace App\Module\User\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Webmozart\Assert\Assert;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class Users
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManager $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function add(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function findByToken(string $token): ?User
    {
        Assert::notEmpty($token);
        Assert::uuid($token);

        $user = $this->repository
            ->createQueryBuilder('user')
            ->join('user.tokens', 'tokens', Join::WITH, 'tokens.id = :tokenId')
            ->setParameter('tokenId', $token)
            ->getQuery()
            ->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($user, User::class);

        return $user;
    }
}
