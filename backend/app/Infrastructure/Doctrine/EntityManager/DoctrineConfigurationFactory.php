<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Фабрика конфигурации Doctrine
 */
final class DoctrineConfigurationFactory
{
    public static function create(
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

        $config->setSchemaAssetsFilter(static function (AbstractAsset|string $assetName): bool {
            if ($assetName instanceof AbstractAsset) {
                $assetName = $assetName->getName();
            }

            return !\in_array($assetName, self::getFilteredAssets(), true);
        });

        $cache = new FilesystemAdapter(
            directory: $cacheDir,
        );

        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);
        $config->setResultCache($cache);

        return $config;
    }

    /**
     * @return list<non-empty-string>
     */
    private static function getFilteredAssets(): array
    {
        /** @var string $jobsTable */
        $jobsTable = config('queue.connections.database.table');

        /** @var string $failedJobsTable */
        $failedJobsTable = config('queue.failed.table');

        /** @var string $jobsBatchingTable */
        $jobsBatchingTable = config('queue.batching.table');

        $ignoredTables = [
            $jobsTable,
            $failedJobsTable,
            $jobsBatchingTable,
        ];

        $ignoredSequences = [
            "{$jobsTable}_id_seq",
            "{$failedJobsTable}_id_seq",
        ];

        /** @var list<non-empty-string> $ignoredAssets */
        $ignoredAssets = array_merge($ignoredTables, $ignoredSequences);

        return $ignoredAssets;
    }
}
