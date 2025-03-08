<?php

declare(strict_types=1);

namespace Dev\OpenApi\Middleware;

use Closure;
use Dev\OpenApi\ValidateOpenApiSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверяет Request и Response на соответствие документации OpenApi
 */
final readonly class ValidateOpenApiSchemaMiddleware
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!App::runningUnitTests()) {
            return $response;
        }

        new ValidateOpenApiSchema()(
            request: $request,
            response: $response,
        );

        return $response;
    }
}
