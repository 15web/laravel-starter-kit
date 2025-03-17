<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\PDO\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use Override;

/**
 * Конструктор запросов
 */
final class Statement extends AbstractStatementMiddleware
{
    use ExecutionTime;

    /**
     * @var array<array-key, mixed>
     */
    public array $params = [];

    public function __construct(
        StatementInterface $statement,
        private string $sql,
    ) {
        parent::__construct($statement);
    }

    #[Override]
    public function bindValue(int|string $param, mixed $value, ParameterType $type = ParameterType::STRING): void
    {
        $this->params[$param] = $value;

        parent::bindValue($param, $value, $type);
    }

    #[Override]
    public function execute(): Result
    {
        /** @var Result */
        return $this->time(fn () => parent::execute(), $this->sql, $this->params);
    }
}
