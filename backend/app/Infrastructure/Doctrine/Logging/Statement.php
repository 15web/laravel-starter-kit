<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Doctrine\DBAL\Driver\Middleware\AbstractStatementMiddleware;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement as StatementInterface;
use Doctrine\DBAL\ParameterType;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final class Statement extends AbstractStatementMiddleware
{
    use ExecutionTime;

    /**
     * @var array<mixed>
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
    public function execute($params = null): Result
    {
        return $this->time(fn () => parent::execute($params), $this->sql, $this->params);
    }
}
