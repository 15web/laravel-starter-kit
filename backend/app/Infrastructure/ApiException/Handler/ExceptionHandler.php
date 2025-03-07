<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Handler;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\BuildValidationError;
use App\Infrastructure\ApiException\Render\ApiExceptionRender;
use CuyZ\Valinor\Mapper\MappingError;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandlerContract;
use Override;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Webmozart\Assert\InvalidArgumentException;

/**
 * Обработчик исключений
 */
final class ExceptionHandler extends ExceptionHandlerContract
{
    protected $dontReport = [
        ApiException::class,
        MappingError::class,
    ];

    public function __construct(
        private readonly BuildValidationError $buildValidationError,
        private readonly ApiExceptionRender $apiExceptionRender,
        Container $container,
    ) {
        parent::__construct($container);
    }

    #[Override]
    public function render($request, Throwable $e): Response
    {
        $specificException = $this->handleSpecificException($e);
        if ($specificException !== null) {
            return ($this->apiExceptionRender)($specificException);
        }

        if ($e instanceof HttpExceptionInterface) {
            $apiException = ApiException::createUnexpectedHttpException($e);

            return ($this->apiExceptionRender)($apiException);
        }

        if ($e instanceof InvalidArgumentException) {
            $apiException = ApiException::createBadRequestException(
                messages: [$e->getMessage()],
                previous: $e,
            );

            return ($this->apiExceptionRender)($apiException);
        }

        if ($e instanceof MappingError) {
            $apiException = ApiException::createBadRequestException(
                messages: ($this->buildValidationError)($e),
                previous: $e,
            );

            return ($this->apiExceptionRender)($apiException);
        }

        if ($e instanceof ApiException) {
            return ($this->apiExceptionRender)($e);
        }

        $unexpectedException = ApiException::createUnexpectedException($e);

        return ($this->apiExceptionRender)($unexpectedException);
    }

    #[Override]
    protected function shouldReturnJson($request, Throwable $e): bool
    {
        return true;
    }

    private function handleSpecificException(Throwable $e): ?ApiException
    {
        if ($e instanceof NotFoundHttpException) {
            return ApiException::createNotFoundException(
                errorMessage: 'Запрашиваемый ресурс не найден.',
                previous: $e,
            );
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            /** @var string $method */
            $method = $e->getHeaders()['Allow'];

            return ApiException::createMethodNotAllowedException(
                errorMessage: "Метод не поддерживается этим ресурсом. Доступные методы: {$method}.",
                previous: $e,
            );
        }

        if ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
            return ApiException::createUnauthenticatedException(
                'Для доступ к ресурсу требуется аутентификация.',
                previous: $e,
            );
        }

        if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
            return ApiException::createForbiddenException(
                errorMessage: 'Доступ к ресурсу ограничен.',
                previous: $e,
            );
        }

        return null;
    }
}
