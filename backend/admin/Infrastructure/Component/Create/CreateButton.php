<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Create;

use Admin\Infrastructure\Router\Attributes\Route;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use ReflectionClass;
use RuntimeException;

/**
 * Кнопка перехода на страницу создания сущности
 */
final class CreateButton extends Component
{
    /**
     * @var class-string
     */
    public string $createEntityComponent;

    /**
     * @var non-empty-string
     */
    public string $createEntityUrl;

    public function mount(): void
    {
        $reflection = new ReflectionClass($this->createEntityComponent);

        $routeAttributes = $reflection->getAttributes(Route::class);

        if ($routeAttributes === []) {
            throw new RuntimeException(
                \sprintf('%s should have %s attribute', $this->createEntityComponent, Route::class),
            );
        }

        $this->createEntityUrl = $routeAttributes[0]->getArguments()[0];
    }

    public function render(): View
    {
        return view('admin.components.entity.create.create-button');
    }
}
