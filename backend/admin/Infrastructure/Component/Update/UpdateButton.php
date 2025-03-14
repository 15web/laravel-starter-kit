<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Update;

use Admin\Infrastructure\Router\Attributes\Route;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use ReflectionClass;
use RuntimeException;

/**
 * Кнопка перехода на страницу редактрирования сущности
 */
final class UpdateButton extends Component
{
    /**
     * @var non-empty-string
     */
    public string $entityId;

    /**
     * @var class-string
     */
    public string $updateEntityComponent;

    /**
     * @var non-empty-string
     */
    public string $updateEntityUrl;

    public function mount(): void
    {
        $reflection = new ReflectionClass($this->updateEntityComponent);

        $routeAttributes = $reflection->getAttributes(Route::class);

        if ($routeAttributes === []) {
            throw new RuntimeException(
                \sprintf('%s should have %s attribute', $this->updateEntityComponent, Route::class),
            );
        }

        $this->updateEntityUrl = str_replace('{id}', $this->entityId, $routeAttributes[0]->getArguments()[0]);
    }

    public function render(): View
    {
        return view('admin.components.entity.update.update-button');
    }
}
