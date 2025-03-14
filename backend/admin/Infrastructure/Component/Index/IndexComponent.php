<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Index;

use Admin\Infrastructure\Component\BaseComponent;
use Admin\Infrastructure\Component\Contract\WithPagination;
use Admin\Infrastructure\Component\Index\Property\Column;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Symfony\Component\Serializer\Serializer;

/**
 * Компонент для отображения таблицы сущностей в админке
 */
abstract class IndexComponent extends BaseComponent
{
    public array $entityList = [];

    public array $tableColumns;

    public int $entityTotal = 0;

    /**
     * @var class-string|null
     */
    public static ?string $createEntityComponent = null;

    /**
     * @var class-string|null
     */
    public static ?string $updateEntityComponent = null;

    /**
     * @return list<array<array-key, mixed>>
     */
    abstract public function getEntityList(): array;

    /**
     * @return non-negative-int
     */
    abstract public function getEntityTotal(): int;

    /**
     * @return non-empty-list<Column>
     */
    abstract public function tableColumns(): array;

    final public function mount(): void
    {
        $this->tableColumns = $this->tableColumns();
    }

    #[Computed]
    final public function canCreateEntity(): bool
    {
        return static::$createEntityComponent !== null;
    }

    #[Computed]
    final public function hasPagination(): bool
    {
        return \in_array(WithPagination::class, class_uses($this), true);
    }

    final public function view(): View
    {
        $this->entityList = app(Serializer::class)->normalize(
            $this->getEntityList(),
        );

        $this->validateEntityId();

        $this->entityTotal = $this->getEntityTotal();

        return view('admin.components.entity.index.list');
    }

    /**
     * @param non-empty-string $entityId
     */
    #[On('delete-entity')]
    final public function deleteEntityListener(Flusher $flusher, string $entityId): void
    {
        static::deleteEntity($entityId);
        $flusher->flush();
    }

    private function validateEntityId(): void
    {
        if ($this->entityList === []) {
            return;
        }

        if (!isset($this->entityList[0]['id'])) {
            throw ApiException::createBadRequestException([
                \sprintf(
                    'Mapped entity should have `id` property, [%s] given',
                    implode(', ', array_keys($this->entityList[0])),
                ),
            ]);
        }
    }
}
