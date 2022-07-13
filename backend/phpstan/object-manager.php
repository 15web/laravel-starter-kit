<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

/** @var Illuminate\Foundation\Application $app */
$app = require __DIR__.'/../bootstrap/app.php';

// Инициируем Laravel Console
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Подключаем EntityManager
return $app->make(Doctrine\ORM\EntityManager::class);
