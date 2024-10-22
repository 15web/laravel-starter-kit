<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

/**
 * Ответ со списком и пагинацией
 */
final readonly class ApiListObjectResponse implements ApiResponse
{
    /**
     * @param iterable<object> $data
     */
    public function __construct(
        public iterable $data,
        public ResponseStatus $status = ResponseStatus::Success,
    ) {}
}
