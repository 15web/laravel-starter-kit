<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Render;

use App\Infrastructure\ApiException\ApiException;

final class ApiExceptionResponse
{
    public function __construct(private ApiException $apiException)
    {
    }

    public function getErrorMessage(): string
    {
        return $this->apiException->getErrorMessage();
    }

    public function getErrorEnum(): string
    {
        return $this->apiException->getErrorEnum()->value;
    }

    public function isError(): bool
    {
        return true;
    }
}
