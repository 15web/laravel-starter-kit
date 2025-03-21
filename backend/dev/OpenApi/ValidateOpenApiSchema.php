<?php

declare(strict_types=1);

namespace Dev\OpenApi;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\RequestValidator;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверяет Request и Response на соответствие документации OpenApi
 */
final readonly class ValidateOpenApiSchema
{
    public const string IGNORE_REQUEST_VALIDATE = 'X-IGNORE-REQUEST-VALIDATE';

    public const string IGNORE_RESPONSE_VALIDATE = 'X-IGNORE-RESPONSE-VALIDATE';

    /**
     * @phpstan-ignore property.uninitializedReadonly
     */
    private RequestValidator $requestValidator;

    /**
     * @phpstan-ignore property.uninitializedReadonly
     */
    private ResponseValidator $responseValidator;

    public function __construct()
    {
        if (app()->isProduction()) {
            return;
        }

        $validatorBuilder = new ValidatorBuilder()->fromYamlFile(
            base_path('dev/openapi.yaml'),
        );

        $this->requestValidator = $validatorBuilder->getRequestValidator();
        $this->responseValidator = $validatorBuilder->getResponseValidator();
    }

    public function __invoke(Request $request, Response $response): void
    {
        if (!$this->needValidate($request->path())) {
            return;
        }

        $this->validateRequest(
            request: $request,
        );

        $this->validateResponse(
            request: $request,
            response: $response,
        );
    }

    private function validateRequest(Request $request): void
    {
        if ($request->hasHeader(self::IGNORE_REQUEST_VALIDATE)) {
            return;
        }

        $psrHttpFactory = $this->buildPsrHttpFactory();
        $psrRequest = $psrHttpFactory->createRequest($request);

        $this->requestValidator->validate($psrRequest);
    }

    private function validateResponse(Request $request, Response $response): void
    {
        if ($request->hasHeader(self::IGNORE_RESPONSE_VALIDATE)) {
            return;
        }

        if (!$response instanceof JsonResponse) {
            return;
        }

        $psrHttpFactory = $this->buildPsrHttpFactory();
        $psrResponse = $psrHttpFactory->createResponse($response);

        $this->responseValidator->validate(
            opAddr: new OperationAddress(
                path: $request->getPathInfo(),
                method: strtolower($request->getMethod()),
            ),
            response: $psrResponse,
        );
    }

    private function needValidate(string $path): bool
    {
        return str_starts_with($path, 'api');
    }

    private function buildPsrHttpFactory(): PsrHttpFactory
    {
        $psr17Factory = new Psr17Factory();

        return new PsrHttpFactory(
            serverRequestFactory: $psr17Factory,
            streamFactory: $psr17Factory,
            uploadedFileFactory: $psr17Factory,
            responseFactory: $psr17Factory,
        );
    }
}
