<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

/**
 * Успешный ответ
 */
final readonly class SuccessResponse
{
    public function __construct(
        public bool $success = true,
    ) {}
}
