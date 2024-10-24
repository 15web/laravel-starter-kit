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
     */
    private function __construct(
        private readonly string $errorMessage,
        private readonly ErrorCode $errorCode,
        private readonly StatusCode $statusCode,
        ?Throwable $previous = null,
        private readonly array $validationErrors = [],
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
     * @param list<string> $messages
     */
    public static function createBadRequestException(array $messages, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: 'Неверный формат запроса',
            errorCode: $errorCode,
            statusCode: StatusCode::BAD_REQUEST,
            previous: $previous,
            validationErrors: $messages,
        );
    }

    public static function createUnauthorizedException(string $errorMessage, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::UNAUTHORIZED,
            previous: $previous,
        );
    }

    public static function createAccessDeniedException(string $errorMessage, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::FORBIDDEN,
            previous: $previous,
        );
    }

    public static function createNotFoundException(string $errorMessage, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::NOT_FOUND,
            previous: $previous
        );
    }

    public static function createMethodNotAllowedException(string $errorMessage, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::METHOD_NOT_ALLOWED,
            previous: $previous
        );
    }

    public static function createDomainException(string $errorMessage, ErrorCode $errorCode, ?Throwable $previous = null): self
    {
        return new self(
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            statusCode: StatusCode::OK,
            previous: $previous
        );
    }

    public static function createUnexpectedHttpException(HttpExceptionInterface $httpException): self
    {
        return new self(
            errorMessage: 'Ошибка фреймворка не обрабатывается приложением. Обратитесь к разработчикам.',
            errorCode: ErrorCode::UNEXPECTED,
            statusCode: StatusCode::SERVER_ERROR,
            previous: $httpException
        );
    }

    public static function createUnexpectedException(Throwable $previous): self
    {
        return new self(
            errorMessage: 'Произошла неожиданная ошибка. Обратитесь к администратору.',
            errorCode: ErrorCode::UNEXPECTED,
            statusCode: StatusCode::SERVER_ERROR,
            previous: $previous
        );
    }
}
