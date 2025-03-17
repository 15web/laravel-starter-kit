<?php

declare(strict_types=1);

namespace App\Infrastructure\OpenApiSchemaValidator\Middleware;

use App\Infrastructure\OpenApiSchemaValidator\ValidateOpenApiSchema;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Проверяет Request и Response на соответствие документации OpenApi
 */
final readonly class ValidateOpenApiSchemaMiddleware
{
    public function __construct(
        private ValidateOpenApiSchema $validateOpenApiSchema,
    ) {}

    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!App::isProduction()) {
            ($this->validateOpenApiSchema)(
                request: $request,
                response: $response,
            );
        }

        return $response;
    }
}
