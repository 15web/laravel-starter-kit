<?php

declare(strict_types=1);

namespace Tests\Feature;

use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * Базовый класс для тестов
 */
#[TestDox('Базовый класс для тестов')]
abstract class FeatureTestCase extends BaseTestCase
{
    use DatabaseTransactions;

    final protected function beginDatabaseTransaction(): void
    {
        $this->getEntityManager()->getConnection()->beginTransaction();

        $this->beforeApplicationDestroyed(
            fn () => $this->getEntityManager()->getConnection()->rollBack(),
        );
    }

    final protected function getEntityManager(): EntityManager
    {
        return $this->app->make(EntityManager::class);
    }
}
