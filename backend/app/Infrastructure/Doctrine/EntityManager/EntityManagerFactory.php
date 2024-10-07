<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class EntityManagerFactory
{
    public static function create(Application $app): EntityManager
    {
        /** @var non-empty-string $connectionName */
        $connectionName = config('database.default');

        /**
         * @see https://www.doctrine-project.org/projects/doctrine-migrations/en/3.8/reference/configuration.html#connection-configuration
         */
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => config("database.connections.{$connectionName}.host"),
            'port' => config("database.connections.{$connectionName}.port"),
            'user' => config("database.connections.{$connectionName}.username"),
            'password' => config("database.connections.{$connectionName}.password"),
            'dbname' => config("database.connections.{$connectionName}.database"),
            'unix_socket' => config("database.connections.{$connectionName}.unix_socket"),
            'charset' => config("database.connections.{$connectionName}.charset"),
        ]);

        $configuration = DoctrineConfigurationFactory::create(
            searchEntitiesPath: $app->path('Module'),
            isDevMode: $app->hasDebugModeEnabled(),
            proxyDir: $app->storagePath('framework/cache/doctrine/orm/Proxies'),
        );

        return new EntityManager(
            conn: $connection,
            config: $configuration,
        );
    }
}
