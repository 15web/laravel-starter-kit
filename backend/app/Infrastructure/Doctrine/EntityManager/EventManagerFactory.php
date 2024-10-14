<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\EntityManager;

use Doctrine\Common\EventManager;
use Gedmo\Mapping\Driver\AttributeReader;
use Gedmo\Tree\TreeListener;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Фабрика для создания Event Manager
 */
final class EventManagerFactory
{
    public static function create(): EventManager
    {
        $eventManager = new EventManager();
        $treeListener = new TreeListener();

        $extensionReader = new AttributeReader();
        $treeListener->setAnnotationReader($extensionReader);

        $cache = new FilesystemAdapter(
            directory: storage_path('framework/cache/doctrine'),
        );
        $treeListener->setCacheItemPool($cache);

        $eventManager->addEventSubscriber($treeListener);

        return $eventManager;
    }
}
