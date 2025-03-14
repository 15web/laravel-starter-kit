<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Index;

use Admin\Infrastructure\Component\Contract\InteractsWithDeleteEntity;
use Admin\Infrastructure\Component\Index\Property\Column;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Компонент для оторажения строки в таблице
 */
final class RowComponent extends Component
{
    /**
     * @var array<array-key, mixed>
     */
    public array $entity;

    /**
     * @var class-string
     */
    public string $indexEntityComponent;

    /**
     * @var class-string|null
     */
    public ?string $updateEntityComponent = null;

    /**
     * @var list<Column>
     */
    public array $tableColumns;

    #[Computed]
    public function canDeleteEntity(): bool
    {
        return \in_array(InteractsWithDeleteEntity::class, class_implements($this->indexEntityComponent), true);
    }

    #[Computed]
    public function canUpdateEntity(): bool
    {
        return $this->updateEntityComponent !== null;
    }

    public function deleteEntity(): void
    {
        $this->dispatch('delete-entity', entityId: $this->entity['id']);
    }

    public function render(): View
    {
        return view('admin.components.entity.index.row');
    }
}
