<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Application;

/**
 * Фабрика для создания Entity Manager
 */
final class EntityManagerFactory
{
    public static function create(Application $app): EntityManager
    {
        /** @var non-empty-string $connectionName */
        $connectionName = config('database.default');

        $dbname = (string) config("database.connections.{$connectionName}.database");
        if ($app->runningUnitTests() && config('database.test_token') !== null) {
            $test_token = (string) config('database.test_token');
            $dbname .= "_{$test_token}";
        }

        /**
         * @see https://www.doctrine-project.org/projects/doctrine-migrations/en/3.8/reference/configuration.html#connection-configuration
         */
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'host' => (string) config("database.connections.{$connectionName}.host"),
            'port' => (int) config("database.connections.{$connectionName}.port"),
            'user' => (string) config("database.connections.{$connectionName}.username"),
            'password' => (string) config("database.connections.{$connectionName}.password"),
            'dbname' => $dbname,
            'unix_socket' => (string) config("database.connections.{$connectionName}.unix_socket"),
            'charset' => (string) config("database.connections.{$connectionName}.charset"),
        ]);

        $configuration = DoctrineConfigurationFactory::create(
            searchEntitiesPath: $app->path('Module'),
            isDevMode: $app->hasDebugModeEnabled(),
            proxyDir: $app->storagePath('framework/cache/doctrine/orm/Proxies'),
        );

        return new EntityManager(
            conn: $connection,
            config: $configuration,
            eventManager: $app->make(EventManager::class),
        );
    }
}
