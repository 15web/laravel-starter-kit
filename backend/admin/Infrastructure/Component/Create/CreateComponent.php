<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Create;

use Admin\Infrastructure\Component\BaseComponent;
use Admin\Infrastructure\Component\Contract\InteractsWithForm;
use App\Infrastructure\Doctrine\Flusher;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use RuntimeException;

/**
 * Компонент для созданий сущности в админке
 */
abstract class CreateComponent extends BaseComponent
{
    use InteractsWithForm;

    /**
     * @var class-string
     */
    public static string $updateEntityComponent;

    /**
     * @var class-string
     */
    public static string $indexEntityComponent;

    /**
     * @return non-empty-string
     */
    abstract public function saveAndReturnEntityId(Flusher $flusher, array $data): string;

    final public function saveForm(bool $redirectToCreate = false): void
    {
        $this->validateFormData();

        $entityId = $this->saveAndReturnEntityId(
            flusher: app(Flusher::class),
            data: $this->data,
        );

        if ($redirectToCreate) {
            $this->redirect(static::class);

            return;
        }

        $this->redirectAction(
            static::$updateEntityComponent,
            ['id' => $entityId],
        );
    }

    final public function mount(): void
    {
        if (!class_exists(static::$indexEntityComponent)) {
            throw new RuntimeException('Define $indexEntityComponent');
        }

        if (!class_exists(static::$updateEntityComponent)) {
            throw new RuntimeException('Define $updateEntityComponent');
        }

        $this->fillFormAndRules();
    }

    final public function boot(): void
    {
        $this->collectErrorMessages();
    }

    #[On('field-updated')]
    final public function setDataValue(string $name, mixed $value): void
    {
        $this->data[$name] = $value;
    }

    final public function view(): View
    {
        return view('admin.components.entity.create.form');
    }
}
