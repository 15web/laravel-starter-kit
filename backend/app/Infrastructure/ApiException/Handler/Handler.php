<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Handler;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Override;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

/**
 * Обработчик исключений
 */
final class Handler extends ExceptionHandler
{
    protected $internalDontReport = [];

    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
    #[Override]
    public function render($request, Throwable $e): Response
    {
        /** @var ApiExceptionRender $apiExceptionRender */
        $apiExceptionRender = $this->container->make(ApiExceptionRender::class);

        $specificException = $this->handleSpecificException($e);
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
    #[Override]
    protected function shouldReturnJson($request, Throwable $e): bool
    {
        return true;
    }

    private function handleSpecificException(Throwable $e): ?ApiException
    {
        if ($e instanceof NotFoundHttpException) {
            /** @var string $message */
            $message = __('handler.not-found');

            return ApiException::createNotFoundException($message, Error::NOT_FOUND, $e);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            /** @var string $method */
            $method = $e->getHeaders()['Allow'];

            /** @var string $message */
            $message = __('handler.method-not-allowed', ['methods' => $method]);

            return ApiException::createMethodNotAllowedException($message, Error::METHOD_NOT_ALLOWED, $e);
        }

        if ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
            /** @var string $message */
            $message = __('handler.not-authenticated');

            return ApiException::createUnauthorizedException($message, Error::UNAUTHORIZED, $e);
        }

        if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
            /** @var string $message */
            $message = __('handler.forbidden');

            return ApiException::createAccessDeniedException($message, Error::ACCESS_DENIED, $e);
        }

        return null;
    }
}
