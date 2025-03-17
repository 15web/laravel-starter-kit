<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use App\Infrastructure\Doctrine\Logging\Middleware as DoctrineLoggingMiddleware;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Фабрика конфигурации Doctrine
 */
final readonly class DoctrineConfigurationFactory
{
    public function __construct(
        #[Config('queue.connections.database.table')]
        private string $jobsTable,
        #[Config('queue.failed.table')]
        private string $failedJobsTable,
        #[Config('queue.batching.table')]
        private string $jobsBatchingTable,
    ) {}

    public function create(
        string $searchEntitiesPath,
        bool $isDevMode,
        string $cacheDir,
    ): Configuration {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [$searchEntitiesPath],
            isDevMode: $isDevMode,
        );

        $proxyDir = "{$cacheDir}/orm/Proxies";

        $config->setProxyNamespace('DoctrineProxies');
        $config->setProxyDir($proxyDir);

        $config->setSchemaAssetsFilter(function (AbstractAsset|string $assetName): bool {
            if ($assetName instanceof AbstractAsset) {
                $assetName = $assetName->getName();
            }

            return !\in_array($assetName, $this->getFilteredAssets(), true);
        });

        $cache = new FilesystemAdapter(
            directory: $cacheDir,
        );

        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);
        $config->setResultCache($cache);

        if (app()->hasDebugModeEnabled()) {
            $config->setMiddlewares([
                new Middleware(Log::getLogger()),
                new DoctrineLoggingMiddleware(),
            ]);
        }

        return $config;
    }

    /**
     * @return list<non-empty-string>
     */
    private function getFilteredAssets(): array
    {
        $ignoredTables = [
            $this->jobsTable,
            $this->failedJobsTable,
            $this->jobsBatchingTable,
        ];

        $ignoredSequences = [
            "{$this->jobsTable}_id_seq",
            "{$this->failedJobsTable}_id_seq",
        ];

        /** @var list<non-empty-string> $ignoredAssets */
        $ignoredAssets = array_merge($ignoredTables, $ignoredSequences);

        return $ignoredAssets;
    }
}
