<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Tools\Setup;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\Psr16Adapter;

final class DoctrineConfigurationFactory
{
    public static function create(
        string $searchEntitiesPath,
        bool $isDevMode,
        string $proxyDir,
        CacheInterface $psr16Cache,
    ): Configuration {
        $config = Setup::createAttributeMetadataConfiguration(
            paths: [$searchEntitiesPath],
            isDevMode: $isDevMode,
        );

        $proxyNamespace = 'DoctrineProxies';

        $config->setProxyNamespace($proxyNamespace);
        $config->setProxyDir($proxyDir);

        $psr6Cache = new Psr16Adapter($psr16Cache);

        $config->setMetadataCache($psr6Cache);
        $config->setQueryCache($psr6Cache);
        $config->setResultCache($psr6Cache);

        return $config;
    }
}
