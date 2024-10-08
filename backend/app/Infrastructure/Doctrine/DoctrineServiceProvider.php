<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Doctrine\EntityManager\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Сервис провайдер для работы с Doctrine
 */
final class DoctrineServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->singleton(
            EntityManager::class,
            static fn (Application $app): EntityManager => EntityManagerFactory::create($app)
        );

        $this->app->singleton(Flusher::class);
    }
}
