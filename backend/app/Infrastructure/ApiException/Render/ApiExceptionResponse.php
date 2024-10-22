<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Render;

use App\Infrastructure\ApiException\ApiException;

/**
 * Подготовленный ответ с ошибкой
 */
final readonly class ApiExceptionResponse
{
    public function __construct(
        private ApiException $apiException,
    ) {}

    public function getMessage(): string
    {
        return $this->apiException->getErrorMessage();
    }

    public function getCode(): string
    {
        return $this->apiException->getErrorCode()->value;
    }

    /**
     * @return list<string>
     */
    public function getErrors(): array
    {
        return $this->apiException->getValidationErrors();
    }
}
