<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Update;

use Admin\Infrastructure\Component\Index\IndexComponent;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Кнопка удаления сущности на странице редактирования
 */
final class DeleteButton extends Component
{
    /**
     * @var class-string<IndexComponent>
     */
    public string $indexEntityComponent;

    /**
     * @var non-empty-string
     */
    public string $entityId;

    #[On('delete-entity')]
    public function deleteEntityListener(string $entityId): void
    {
        $this->indexEntityComponent::deleteEntity($entityId);
        $this->redirect($this->indexEntityComponent);
    }

    public function render(): View
    {
        return view('admin.components.entity.update.delete-button');
    }
}
