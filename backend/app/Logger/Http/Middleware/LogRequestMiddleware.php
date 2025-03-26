<?php

declare(strict_types=1);

namespace App\Logger\Http\Middleware;

use App\Ping\Http\PingAction;
use Closure;
use CuyZ\Valinor\Mapper\Source\JsonSource;
use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Normalizer\ArrayNormalizer;
use CuyZ\Valinor\Normalizer\Format;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Log;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

/**
 * Запись запроса и ответа в лог
 */
final readonly class LogRequestMiddleware
{
    private const array IGNORED_ACTIONS = [
        PingAction::class,
    ];

    private ArrayNormalizer $normalizer;

    public function __construct(MapperBuilder $builder)
    {
        $this->normalizer = $builder->normalizer(Format::array());
    }

    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$this->shouldBeLogged($request)) {
            return $response;
        }

        $requestId = (string) new UuidV7();

        $this->logRequest(
            request: $request,
            requestId: $requestId,
        );

        $response->headers->set('X-Trace-Id', $requestId);

        if (class_exists(TelescopeServiceProvider::class)) {
            Telescope::tag(
                static fn (IncomingEntry $entry): array => $entry->type === 'request' ? [$requestId] : [],
            );

            $response->headers->set(
                key: 'X-Telescope',
                values: url(\sprintf('/telescope/requests?tag=%s', $requestId)),
            );
        }

        $this->logResponse(
            response: $response,
            requestId: $requestId,
        );

        return $response;
    }

    private function logRequest(Request $request, string $requestId): void
    {
        $error = null;

        try {
            $payload = $request->getPayload()->all();
        } catch (JsonException $e) {
            $error = $e->getMessage();
            $payload = $request->getContent();
        }

        $message = \sprintf('<<< %s %s', $request->getMethod(), $request->getRequestUri());

        $context = [
            'payload' => $payload,
            'headers' => $this->collectHeaders($request->headers),
            'ip' => $request->getClientIp(),
            'error' => $error,
            'requestId' => $requestId,
        ];

        /** @var Uuid|null $authenticatedUserId */
        $authenticatedUserId = auth()->id();

        if ($authenticatedUserId !== null) {
            $context['authId'] = (string) $authenticatedUserId;
        }

        Log::info($message, $context);
    }

    private function logResponse(Response $response, string $requestId): void
    {
        $message = \sprintf('>>> %s', $response->getStatusCode());

        Log::info($message, [
            'content' => $this->prepareResponseContent($response),
            'headers' => $this->collectHeaders($response->headers),
            'requestId' => $requestId,
        ]);
    }

    private function shouldBeLogged(Request $request): bool
    {
        /** @var Route|null $route */
        $route = $request->route();
        if ($route === null) {
            return false;
        }

        /** @var non-empty-string|null $actionClass */
        $actionClass = $route->getControllerClass();

        if ($actionClass === null) {
            return false;
        }

        if (\in_array($actionClass, self::IGNORED_ACTIONS, true)) {
            return false;
        }

        return str_starts_with($route->uri(), 'api');
    }

    /**
     * @return array<string, string|null>
     */
    private function collectHeaders(HeaderBag $headerBag): array
    {
        /** @var array<string, list<string|null>> $headers */
        $headers = $headerBag->all();

        $mapped = array_map(
            callback: static fn (array $header): ?string => $header[0],
            array: $headers,
        );

        if (\array_key_exists('authorization', $mapped)) {
            $mapped['authorization'] = '***';
        }

        return $mapped;
    }

    /**
     * @return array<array-key, mixed>|string
     */
    private function prepareResponseContent(Response $response): array|string
    {
        $content = (string) $response->getContent();
        if (blank($content)) {
            $content = '[]';
        }

        if (!json_validate($content)) {
            return $content;
        }

        /** @var array<array-key, mixed> */
        return $this->normalizer->normalize(
            new JsonSource($content),
        );
    }
}
