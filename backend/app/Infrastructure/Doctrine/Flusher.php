<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * Флашер
 */
final readonly class Flusher
{
    public function __construct(
        private EntityManager $entityManager,
    ) {}

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function clear(): void
    {
        $this->entityManager->clear();
    }

    public function refresh(object $object): void
    {
        $this->entityManager->refresh($object);
    }
}
