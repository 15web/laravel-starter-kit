<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Update;

use Admin\Infrastructure\Component\BaseComponent;
use Admin\Infrastructure\Component\Contract\InteractsWithDeleteEntity;
use Admin\Infrastructure\Component\Contract\InteractsWithForm;
use Admin\Infrastructure\Component\Field\Field;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use RuntimeException;
use Symfony\Component\Serializer\Serializer;

/**
 * Компонент для редактирования сущности в админке
 */
abstract class UpdateComponent extends BaseComponent
{
    use InteractsWithForm;

    /**
     * @var class-string
     */
    public static string $indexEntityComponent;

    /**
     * @var non-empty-string
     */
    public string $entityId;

    /**
     * @var array<array-key, mixed>
     */
    public array $entity;

    #[Computed]
    final public function canDeleteEntity(): bool
    {
        return \in_array(InteractsWithDeleteEntity::class, class_implements(static::$indexEntityComponent), true);
    }

    abstract public function updateAndReturnEntity(Flusher $flusher, string $entityId, array $data): object;

    /**
     * @param non-empty-string $entityId
     */
    abstract public function resolveEntity(string $entityId): ?object;

    /**
     * @param non-empty-string $id
     */
    final public function mount(Serializer $serializer, string $id): void
    {
        if (!class_exists(static::$indexEntityComponent)) {
            throw new RuntimeException('Define $indexEntityComponent');
        }

        $this->entityId = $id;

        $entity = $this->resolveEntity($this->entityId);

        if ($entity === null) {
            throw ApiException::createNotFoundException(
                \sprintf('Entity [%s] not found', $this->entityId),
            );
        }

        $this->entity = $serializer->normalize($entity);

        $this->fillFormData();
    }

    final public function saveForm(Serializer $serializer): void
    {
        $this->validateFormData();

        $this->entity = $serializer->normalize(
            $this->updateAndReturnEntity(
                flusher: app(Flusher::class),
                entityId: $this->entityId,
                data: $this->data,
            ),
        );

        $this->fillFormData();
    }

    final public function boot(): void
    {
        $this->collectErrorMessages();
    }

    final public function view(): View
    {
        return view('admin.components.entity.update.form');
    }

    final protected function fillFormData(): void
    {
        $this->fillFormAndRules();

        $this->data = collect($this->formFields)
            ->mapWithKeys(static fn (Field $field): array => [
                $field->name => $field->value,
            ])
            ->all();
    }
}
