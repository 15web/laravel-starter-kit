<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Container\Attributes\Config;
use Illuminate\Foundation\Application;

/**
 * Фабрика для создания Entity Manager
 */
final readonly class EntityManagerFactory
{
    /**
     * @param non-empty-string $connectionName
     * @param array<string, array{database: string}> $databaseConnections
     */
    public function __construct(
        #[Config('database.default')]
        private string $connectionName,
        #[Config('database.test_token')]
        private string $testToken,
        #[Config('database.connections')]
        private array $databaseConnections,
    ) {}

    public function create(Application $app): EntityManager
    {
        $dbname = $this->databaseConnections[$this->connectionName]['database'];
        if ($app->runningUnitTests() && config('database.test_token') !== null) {
            $dbname .= "_{$this->testToken}";
        }

        /** @var string $env */
        $env = $app->environment();

        $configuration = $app->make(DoctrineConfigurationFactory::class)->create(
            searchEntitiesPath: $app->path(),
            isDevMode: $app->hasDebugModeEnabled(),
            cacheDir: $app->storagePath("framework/cache/doctrine/{$env}"),
        );

        /** @var string $dbHost */
        $dbHost = config("database.connections.{$this->connectionName}.host");

        /** @var int $dbPort */
        $dbPort = config("database.connections.{$this->connectionName}.port");

        /** @var string $dbUser */
        $dbUser = config("database.connections.{$this->connectionName}.username");

        /** @var string $dbPassword */
        $dbPassword = config("database.connections.{$this->connectionName}.password");

        /** @var string $dbUnixSocket */
        $dbUnixSocket = config("database.connections.{$this->connectionName}.unix_socket");

        /** @var string $dbCharset */
        $dbCharset = config("database.connections.{$this->connectionName}.charset");

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
