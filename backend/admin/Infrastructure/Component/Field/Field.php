<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Field;

use Illuminate\Support\Str;
use Livewire\Wireable;

/**
 * Базовый класс поля формы
 */
abstract class Field implements Wireable
{
    public function __construct(
        public string $id,
        public string $name,
        public string $label,
        public mixed $value = null,
        public array $rules = [],
        public array $options = [],
    ) {}

    final public static function make(
        string $name,
        string $label,
        mixed $value = null,
        array $rules = [],
        array $options = [],
    ): static {
        return new static(
            id: 'f-'.Str::random(),
            name: $name,
            label: $label,
            value: $value,
            rules: $rules,
            options: $options,
        );
    }

    final public function toLivewire(): array
    {
        return [
            'id' => $this->id,
            'component' => static::$component,
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'rules' => $this->rules,
            'options' => $this->options,
        ];
    }

    final public static function fromLivewire($value): static
    {
        return new static(
            id: $value['id'],
            name: $value['name'],
            label: $value['label'],
            value: $value['value'],
            rules: $value['rules'],
            options: $value['options'],
        );
    }
}
