<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Router;

use Admin\Infrastructure\Router\Attributes\Route;
use Illuminate\Support\Facades\Route as Router;
use Illuminate\Support\Str;
use ReflectionClass;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Регистратор роутов админки
 */
final class RouteRegistrar
{
    private readonly string $basePath;

    /**
     * @todo Перетащить в конфиг
     */
    private string $prefix = 'admin';

    /**
     * @todo Перетащить в конфиг
     */
    private array $middlewares = ['web'];

    public function __construct()
    {
        $this->basePath = str_replace(['/', '\\'], \DIRECTORY_SEPARATOR, base_path('admin'));
    }

    public function __invoke(): void
    {
        $files = new Finder()
            ->files()
            ->in($this->basePath)
            ->exclude(\sprintf('%s/Infrastructure', $this->basePath))
            ->name(['*.php'])
            ->sortByName();

        collect($files)->each(fn (SplFileInfo $file) => $this->registerFile($file));
    }

    public function addMiddlewareToRoute(Route $attributeClass, \Illuminate\Routing\Route $route): void
    {
        $route->middleware([...$this->middlewares, ...$attributeClass->middleware]);
    }

    private function registerFile(SplFileInfo $file): void
    {
        $fullyQualifiedClassName = $this->fullQualifiedClassNameFromFile($file);

        $this->processAttributes($fullyQualifiedClassName);
    }

    private function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        return Str::of($file->getRealPath())
            ->replaceFirst($this->basePath, '')
            ->trim(\DIRECTORY_SEPARATOR)
            ->replaceLast('.php', '')
            ->ucfirst()
            ->replace([\DIRECTORY_SEPARATOR], '\\')
            ->prepend('Admin\\')
            ->toString();
    }

    private function processAttributes(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        $class = new ReflectionClass($className);
        $attributes = $class->getAttributes(Route::class);
        if ($attributes === []) {
            return;
        }

        $attributeClass = $attributes[0]->newInstance();

        $uri = \sprintf(
            '%s/%s',
            rtrim($this->prefix, '/'),
            ltrim($attributeClass->uri, '/'),
        );

        $route = Router::addRoute('GET', $uri, $class->getName())->name(
            $attributeClass->name,
        );

        $this->addMiddlewareToRoute($attributeClass, $route);
    }
}
