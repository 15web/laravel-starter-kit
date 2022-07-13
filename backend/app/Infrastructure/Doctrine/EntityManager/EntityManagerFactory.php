<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;

final class EntityManagerFactory
{
    public static function create(Application $app): EntityManager
    {
        $connection = DriverManager::getConnection([
            'url' => config('doctrine.connection_url'),
        ]);

        $configuration = DoctrineConfigurationFactory::create(
            searchEntitiesPath: $app->path('Module'),
            isDevMode: $app->hasDebugModeEnabled(),
            proxyDir: $app->storagePath('framework/cache/doctrine/orm/Proxies'),
            psr16Cache: cache()->store(),
        );

        return EntityManager::create($connection, $configuration);
    }
}
