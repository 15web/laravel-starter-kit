<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Driver\Middleware as MiddlewareInterface;
use Override;

/**
 * Миддлвар для запросов Doctrine
 *
 * @see https://github.com/cheack/debugbar-doctrine
 */
final class Middleware implements MiddlewareInterface
{
    #[Override]
    public function wrap(DriverInterface $driver): DriverInterface
    {
        return new Driver($driver);
    }
}
