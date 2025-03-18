<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\PDO\Result;
use Illuminate\Database\Events\QueryExecuted;

use function microtime;

/**
 * Замеряет время выполнения запроса
 */
trait ExecutionTime
{
    /**
     * Measure the execution time of a callable and log it as a query execution event.
     *
     * @param array<array-key, mixed> $params
     */
    protected function time(callable $callable, string $sql, ?array $params = null): Result
    {
        $start = microtime(true);

        /** @var Result $result */
        $result = $callable();

        $end = microtime(true);

        event(
            new QueryExecuted(
                sql: $sql,
                bindings: $params !== null && $params !== [] ? $params : [],
                time: ($end - $start) * 1000.0,
                connection: new StubDatabaseConnection(),
            ),
        );

        return $result;
    }
}
