<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Override;

/**
 * Собирает данные о запросе
 */
final class Connection extends AbstractConnectionMiddleware
{
    use ExecutionTime;

    #[Override]
    public function prepare(string $sql): StatementInterface
    {
        return new Statement(parent::prepare($sql), $sql);
    }

    #[Override]
    public function query(string $sql): \Doctrine\DBAL\Driver\PDO\Result
    {
        /** @var \Doctrine\DBAL\Driver\PDO\Result */
        return $this->time(fn (): Result => parent::query($sql), $sql);
    }
}
