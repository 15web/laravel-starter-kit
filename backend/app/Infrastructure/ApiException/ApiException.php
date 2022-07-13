<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException;

use App\Contract\Error;
use App\Infrastructure\ApiException\Http\StatusCode;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ApiException extends \Exception
{
    private function __construct(
        private readonly string $errorMessage,
        private readonly Error $errorEnum,
        private readonly StatusCode $statusCode,
        ?\Throwable $previous = null
    ) {
        parent::__construct(previous: $previous);
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorEnum(): Error
    {
        return $this->errorEnum;
    }

    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }

    public static function createBadRequestException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::BAD_REQUEST,
            $previous
        );
    }

    public static function createUnauthorizedException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::UNAUTHORIZED,
            $previous
        );
    }

    public static function createAccessDeniedException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::FORBIDDEN,
            $previous
        );
    }

    public static function createNotFoundException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::NOT_FOUND,
            $previous
        );
    }

    public static function createMethodNotAllowedException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::METHOD_NOT_ALLOWED,
            $previous
        );
    }

    public static function createDomainException(string $errorMessage, Error $errorEnum, ?\Throwable $previous = null): self
    {
        return new self(
            $errorMessage,
            $errorEnum,
            StatusCode::OK,
            $previous
        );
    }

    public static function createUnexpectedHttpException(HttpExceptionInterface $httpException): self
    {
        return new self(
            'Ошибка фреймворка не обрабатывается приложением. Обратитесь к разработчикам бэкенда.',
            Error::UNEXPECTED,
            StatusCode::SERVER_ERROR,
            $httpException,
        );
    }

    public static function createUnexpectedException(\Throwable $previous): self
    {
        return new self(
            'Произошла неожиданная ошибка. Обратитесь к администратору',
            Error::UNEXPECTED,
            StatusCode::SERVER_ERROR,
            $previous
        );
    }
}
