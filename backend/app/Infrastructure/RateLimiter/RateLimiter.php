<?php

declare(strict_types=1);

namespace App\Infrastructure\RateLimiter;

use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;

/**
 * Проверяет количество попыток запроса
 */
final readonly class RateLimiter
{
    /**
     * @param non-empty-string $key
     * @param positive-int $maxAttempts
     */
    public function __construct(
        private string $key,
        private int $maxAttempts,
    ) {}

    public function isExceeded(): bool
    {
        return RateLimiterFacade::tooManyAttempts(
            key: $this->key,
            maxAttempts: $this->maxAttempts,
        );
    }

    public function increment(): void
    {
        RateLimiterFacade::increment($this->key);
    }

    public function clear(): void
    {
        RateLimiterFacade::clear($this->key);
    }

    public function availableIn(): int
    {
        return RateLimiterFacade::availableIn($this->key);
    }

    /**
     * @return positive-int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }
}
