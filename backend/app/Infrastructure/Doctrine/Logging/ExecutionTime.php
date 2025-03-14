<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Illuminate\Database\Events\QueryExecuted;

use function microtime;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
trait ExecutionTime
{
    /**
     * Measure the execution time of a callable and log it as a query execution event.
     */
    protected function time(callable $callable, string $sql, ?array $params = null): mixed
    {
        $start = microtime(true);
        $result = $callable();
        $end = microtime(true);

        event(new QueryExecuted($sql, $params !== null && $params !== [] ? $params : [], ($end - $start) * 1000, new StubDatabaseConnection()));

        return $result;
    }
}
