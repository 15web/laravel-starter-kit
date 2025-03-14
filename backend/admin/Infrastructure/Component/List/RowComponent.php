<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\List;

use Admin\Infrastructure\Component\List\Property\Column;
use Illuminate\Contracts\View\View;
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
     * @var list<Column>
     */
    public array $table;

    public function deleteEntity(): void
    {
        $this->dispatch('delete-entity', entityId: $this->entity['id']);
    }

    public function render(): View
    {
        return view('admin.components.entity.list.row');
    }
}
