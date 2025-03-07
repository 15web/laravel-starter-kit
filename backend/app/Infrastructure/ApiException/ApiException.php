<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException;

use App\Infrastructure\ApiException\Handler\ErrorCode;
use App\Infrastructure\ApiException\Http\StatusCode;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Базовое API исключение
 */
final class ApiException extends Exception
{
    /**
     * @param list<string> $validationErrors
     * @param array<non-empty-string, string> $headers
     */
    private function __construct(
        private readonly string $errorMessage,
        private readonly ErrorCode $errorCode,
        private readonly StatusCode $statusCode,
        ?Throwable $previous = null,
        private readonly array $validationErrors = [],
        private readonly array $headers = [],
    ) {
        parent::__construct(previous: $previous);
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }

    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    /**
     * @return list<string>
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }

    /**
     * @return array<non-empty-string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param list<string> $messages
     */
    public static function createBadRequestException(
        array $messages,
        ErrorCode $errorCode = ErrorCode::BAD_REQUEST,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: 'Неверный формат запроса',
            errorCode: $errorCode,
            statusCode: StatusCode::BAD_REQUEST,
            previous: $previous,
            validationErrors: $messages,
        );
    }

    public static function createUnauthenticatedException(
        string $errorMessage,
        ErrorCode $errorCode = ErrorCode::UNAUTHENTICATED,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::UNAUTHENTICATED,
            previous: $previous,
        );
    }

    public static function createForbiddenException(
        string $errorMessage,
        ErrorCode $errorCode = ErrorCode::FORBIDDEN,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::FORBIDDEN,
            previous: $previous,
        );
    }

    public static function createNotFoundException(
        string $errorMessage,
        ErrorCode $errorCode = ErrorCode::NOT_FOUND,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::NOT_FOUND,
            previous: $previous,
        );
    }

    public static function createMethodNotAllowedException(
        string $errorMessage,
        ErrorCode $errorCode = ErrorCode::METHOD_NOT_ALLOWED,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::METHOD_NOT_ALLOWED,
            previous: $previous,
        );
    }

    public static function createDomainException(
        string $errorMessage,
        ErrorCode $errorCode,
        ?Throwable $previous = null,
    ): self {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::OK,
            previous: $previous,
        );
    }

    public static function createUnexpectedHttpException(HttpExceptionInterface $httpException): self
    {
        return new self(
            errorMessage: 'Ошибка фреймворка не обрабатывается приложением. Обратитесь к разработчикам.',
            errorCode: ErrorCode::UNEXPECTED,
            statusCode: StatusCode::SERVER_ERROR,
            previous: $httpException,
        );
    }

    public static function createUnexpectedException(Throwable $previous): self
    {
        return new self(
            errorMessage: 'Произошла неожиданная ошибка. Обратитесь к администратору.',
            errorCode: ErrorCode::UNEXPECTED,
            statusCode: StatusCode::SERVER_ERROR,
            previous: $previous,
        );
    }

    public static function createTooManyRequestsException(int $retryAfter, int $limit): self
    {
        return new self(
            errorMessage: 'Превышено количество запросов.',
            errorCode: ErrorCode::TOO_MANY_REQUESTS,
            statusCode: StatusCode::TOO_MANY_REQUESTS,
            headers: [
                'X-RateLimit-Retry-After' => (string) $retryAfter,
                'X-RateLimit-Limit' => (string) $limit,
            ],
        );
    }
}
