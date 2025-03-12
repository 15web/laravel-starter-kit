<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Attributes\Config;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Yaml\Yaml;

/**
 * Проверяет расхождения endpoints и документации OpenApi
 */
#[AsCommand(name: 'openapi-routes-diff', description: 'Находит расхождения endpoints и документации openapi')]
final class OpenApiRoutesDiffCommand extends Command
{
    public function handle(
        #[Config('openapi.path')]
        string $openApiPath,
    ): int {
        $openApiPaths = $this->getOpenApiPaths($openApiPath);
        $appPaths = $this->getAppPaths();
        $missingAppPaths = array_diff($openApiPaths, $appPaths);
        $missingOpenApiPaths = array_diff($appPaths, $openApiPaths);

        if ($missingAppPaths === [] && $missingOpenApiPaths === []) {
            $this->info('Расхождения endpoints и документации openapi не найдены');

            return Command::SUCCESS;
        }

        if ($missingAppPaths !== []) {
            $this->table(['Найдены пути, которые не реализованы в приложении'], array_map(
                callback: static fn (string $path): array => [$path],
                array: $missingAppPaths,
            ));
        }

        if ($missingOpenApiPaths !== []) {
            $this->table(['Найдены endpoints, которые не описаны'], array_map(
                callback: static fn (string $path): array => [$path],
                array: $missingOpenApiPaths,
            ));
        }

        return Command::FAILURE;
    }

    /**
     * @return string[]
     */
    private function getOpenApiPaths(string $openApiPath): array
    {
        /**
         * @var array{
         *     paths?: array{
         *         non-empty-list: array{
         *             non-empty-string: mixed
         *         }
         *     }
         * } $openApiValues
         */
        $openApiValues = (array) Yaml::parseFile(
            base_path($openApiPath),
        );

        if (!\array_key_exists('paths', $openApiValues)) {
            throw new InvalidArgumentException('Invalid yaml file');
        }

        $result = [];

        foreach ($openApiValues['paths'] as $path => $methods) {
            foreach (array_keys($methods) as $method) {
                $result[] = strtoupper($method).' /api'.$path;
            }
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function getAppPaths(): array
    {
        $result = [];

        /** @var list<\Illuminate\Routing\Route> $routes */
        $routes = Route::getRoutes()->getRoutes();

        foreach ($routes as $route) {
            $path = $route->uri();

            if (!str_starts_with($path, 'api')) {
                continue;
            }

            /** @var non-empty-string $method */
            $method = $route->methods()[0];

            $result[] = $method.' /'.ltrim($path, '/');
        }

        return $result;
    }
}
