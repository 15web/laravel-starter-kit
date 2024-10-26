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

        /** @var string $dbname */
        $dbname = config("database.connections.{$connectionName}.database");
        if ($app->runningUnitTests() && config('database.test_token') !== null) {
            /** @var string $testToken */
            $testToken = config('database.test_token');
            $dbname .= "_{$testToken}";
        }

        $configuration = DoctrineConfigurationFactory::create(
            searchEntitiesPath: $app->path(),
            isDevMode: $app->hasDebugModeEnabled(),
            proxyDir: $app->storagePath('framework/cache/doctrine/orm/Proxies'),
        );

        /** @var string $dbHost */
        $dbHost = config("database.connections.{$connectionName}.host");

        /** @var int $dbPort */
        $dbPort = config("database.connections.{$connectionName}.port");

        /** @var string $dbUser */
        $dbUser = config("database.connections.{$connectionName}.username");

        /** @var string $dbPassword */
        $dbPassword = config("database.connections.{$connectionName}.password");

        /** @var string $dbUnixSocket */
        $dbUnixSocket = config("database.connections.{$connectionName}.unix_socket");

        /** @var string $dbCharset */
        $dbCharset = config("database.connections.{$connectionName}.charset");

        /**
         * @see https://www.doctrine-project.org/projects/doctrine-migrations/en/3.8/reference/configuration.html#connection-configuration
         */
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_pgsql',
            'host' => $dbHost,
            'port' => $dbPort,
            'user' => $dbUser,
            'password' => $dbPassword,
            'dbname' => $dbname,
            'unix_socket' => $dbUnixSocket,
            'charset' => $dbCharset,
        ], $configuration);

        return new EntityManager(
            conn: $connection,
            config: $configuration,
            eventManager: $app->make(EventManager::class),
        );
    }
}
