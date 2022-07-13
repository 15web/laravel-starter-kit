<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Infrastructure\Doctrine\EntityManager\EntityManagerFactory;
use function base_path;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class DoctrineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(base_path('config/doctrine.php'), 'doctrine');

        $this->app->singleton(
            EntityManager::class,
            static fn (Application $app) => EntityManagerFactory::create($app)
        );

        $this->app->singleton(Flusher::class);
    }
}
