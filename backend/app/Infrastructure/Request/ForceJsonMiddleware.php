<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use App\Infrastructure\ApiException\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Response;

/**
 * Принудительно добавляет заголовок Accept
 */
final class ForceJsonMiddleware
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acceptableContentTypes = $this->getAcceptableContentTypes($request);

        if (\in_array('application/json', $acceptableContentTypes, true)) {
            return $next($request);
        }

        if (
            $acceptableContentTypes === []
            || \in_array('*/*', $acceptableContentTypes, true)
            || \in_array('application/*', $acceptableContentTypes, true)
        ) {
            $request->headers->set('Accept', 'application/json');

            return $next($request);
        }

        throw ApiException::createBadRequestException(['Укажите заголовок Accept: application/json']);
    }

    /**
     * @return list<string>
     */
    private function getAcceptableContentTypes(Request $request): array
    {
        $acceptableContentTypes = AcceptHeader::fromString(
            $request->headers->get('Accept'),
        )->all();

        /** @var list<string> */
        return collect($acceptableContentTypes)
            ->keys()
            ->map(static fn (int|string $key): string => (string) $key)
            ->all();
    }
}
