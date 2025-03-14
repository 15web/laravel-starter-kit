<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Field\Component;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Базовый компонент для поля формы
 */
abstract class FieldComponent extends Component
{
    public string $view;

    public string $name;

    public string $label;

    public mixed $value;

    public array $options = [];

    public array $rules = [];

    final public function render(): View
    {
        return view($this->view);
    }
}
