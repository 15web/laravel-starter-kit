<?php

declare(strict_types=1);

namespace App\User\User\Query;

use App\User\User\Domain\User;
use Doctrine\ORM\EntityManager;

/**
 * Поиск пользователей по имени
 */
final readonly class FindUsersByPattern
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    /**
     * @param non-empty-string $pattern
     * @param positive-int $limit
     *
     * @return array<non-empty-string, non-empty-string>
     */
    public function __invoke(string $pattern, int $limit = 10): array
    {
        /** @var list<User> $users */
        $users = $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->where('u.email LIKE :pattern')
            ->setParameter('pattern', "%{$pattern}%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return collect($users)
            ->mapWithKeys(static fn (User $user): array => [
                (string) $user->getId() => $user->getEmail(),
            ])
            ->all();
    }
}
