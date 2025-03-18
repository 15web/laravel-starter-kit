<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Logging;

use Illuminate\Database\Connection;
use Override;

/**
 * A stub implementation of Laravel's database connection class.
 * This class is used to fulfill the requirement of a database connection
 * when dispatching the `QueryExecuted` event, even though an actual connection
 * is not necessary. It provides a minimal implementation to satisfy the interface.
 */
final class StubDatabaseConnection extends Connection
{
    public function __construct()
    {
        // No need to call the parent constructor
        $this->useDefaultQueryGrammar();
        $this->useDefaultPostProcessor();
    }

    #[Override]
    public function getName(): ?string
    {
        return null;
    }
}
