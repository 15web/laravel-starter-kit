<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class DoctrineConfigurationFactory
{
    public static function create(
        string $searchEntitiesPath,
        bool $isDevMode,
        string $proxyDir,
    ): Configuration {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [$searchEntitiesPath],
            isDevMode: $isDevMode,
        );

        $config->setProxyNamespace('DoctrineProxies');
        $config->setProxyDir($proxyDir);

        $cache = new FilesystemAdapter(
            directory: storage_path('framework/cache/doctrine'),
        );

        $config->setMetadataCache($cache);
        $config->setQueryCache($cache);
        $config->setResultCache($cache);

        return $config;
    }
}
