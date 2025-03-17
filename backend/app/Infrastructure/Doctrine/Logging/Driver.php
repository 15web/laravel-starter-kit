<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;
use Override;

/**
 * Драйвер подключения к базе данных
 */
final class Driver extends AbstractDriverMiddleware
{
    #[Override]
    public function connect(array $params): Connection
    {
        return new Connection(parent::connect($params));
    }
}
