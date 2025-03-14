<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\Middleware\AbstractConnectionMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
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
    public function query(string $sql): Result
    {
        return $this->time(fn () => parent::query($sql), $sql);
    }
}
