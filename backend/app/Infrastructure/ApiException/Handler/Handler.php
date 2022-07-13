<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Handler;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class Handler extends ExceptionHandler
{
    protected $internalDontReport = [];

    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
    public function render($request, \Throwable $e): Response
    {
        $apiExceptionRender = $this->container->make(ApiExceptionRender::class);

        $specificException = self::handleSpecificException($e);
        if ($specificException !== null) {
            return ($apiExceptionRender)($specificException);
        }

        if ($e instanceof HttpExceptionInterface) {
            $apiException = ApiException::createUnexpectedHttpException($e);

            return ($apiExceptionRender)($apiException);
        }

        if ($e instanceof ApiException) {
            return ($apiExceptionRender)($e);
        }

        $unexpectedException = ApiException::createUnexpectedException($e);

        return ($apiExceptionRender)($unexpectedException);
    }

    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
    protected function shouldReturnJson($request, \Throwable $e): bool
    {
        return true;
    }

    private static function handleSpecificException(\Throwable $e): ?\Throwable
    {
        if ($e instanceof NotFoundHttpException) {
            return ApiException::createNotFoundException('Запрашиваемый ресурс не найден.', Error::NOT_FOUND, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $message = sprintf('Метод не поддерживается этим ресурсом. Доступные методы: %s.', $e->getHeaders()['Allow']);

            return ApiException::createMethodNotAllowedException($message, Error::METHOD_NOT_ALLOWED, $e);
        }

        if ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
            return ApiException::createUnauthorizedException('Для доступ к ресурсу требуется аутентификация.', Error::UNAUTHORIZED, $e);
        }

        if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
            return ApiException::createAccessDeniedException('Доступ к ресурсу ограничен.', Error::ACCESS_DENIED, $e);
        }

        return null;
    }
}
