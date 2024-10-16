<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenApiSchemaValidator;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
    public const string VALIDATE_REQUEST_KEY = 'validate_request';

    public const string VALIDATE_RESPONSE_KEY = 'validate_response';

    private RequestValidator $requestValidator;

    private ResponseValidator $responseValidator;

    public function __construct()
    {
        $openApiPath = (string) config('openapi.path');
        $validatorBuilder = (new ValidatorBuilder())->fromYamlFile(
            base_path($openApiPath)
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
        if (!$this->needValidateTest($request, self::VALIDATE_REQUEST_KEY)) {
            return;
        }

        $psrHttpFactory = $this->buildPsrHttpFactory();
        $psrRequest = $psrHttpFactory->createRequest($request);

        $this->requestValidator->validate($psrRequest);
    }

    private function validateResponse(Request $request, Response $response): void
    {
        if (!$this->needValidateTest($request, self::VALIDATE_RESPONSE_KEY)) {
            return;
        }

        if (!$response instanceof JsonResponse) {
            return;
        }

        $psrHttpFactory = $this->buildPsrHttpFactory();
        $psrResponse = $psrHttpFactory->createResponse($response);

        $this->responseValidator->validate(
            opAddr: new OperationAddress(
                $request->getPathInfo(),
                strtolower($request->getMethod())
            ),
            response: $psrResponse
        );
    }

    private function needValidate(string $path): bool
    {
        return str_starts_with($path, 'api');
    }

    private function needValidateTest(Request $request, string $requestParameterName): bool
    {
        $parameterValue = (bool) $request->get($requestParameterName, true);

        return App::runningUnitTests() && $parameterValue === true;
    }

    private function buildPsrHttpFactory(): PsrHttpFactory
    {
        $psr17Factory = new Psr17Factory();

        return new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
    }
}
