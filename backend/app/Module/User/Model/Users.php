<?php

declare(strict_types=1);

namespace App\Module\User\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

final class Users
{
    /**
     * @var EntityRepository<User>
     */
    private EntityRepository $repository;

    public function __construct(private readonly EntityManager $entityManager)
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

        $user = $this->repository->createQueryBuilder('user')
            ->join('user.tokens', 'tokens', 'WITH', 'tokens.id = :tokenId')
            ->setParameter('tokenId', $token)
            ->getQuery()->getOneOrNullResult();

        Assert::nullOrIsInstanceOf($user, User::class);

        return $user;
    }
}
