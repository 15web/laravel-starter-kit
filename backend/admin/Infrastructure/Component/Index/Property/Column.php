<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Index\Property;

use Closure;
use Illuminate\Support\Str;
use Laravel\SerializableClosure\Serializers\Native;
use Livewire\Wireable;
use Stringable;

/**
 * Столбец для таблицы
 */
final class Column implements Wireable
{
    /**
     * @param non-empty-string $name
     * @param int<3|4> $defaultSort
     * @param Closure(mixed): (string|Stringable)|null $formatted
     */
    public function __construct(
        public string $name,
        public ?string $label = null,
        public bool $sortable = false,
        public int $defaultSort = SORT_DESC,
        public ?Closure $formatted = null,
    ) {
        if ($this->label === null) {
            $this->label = Str::headline($this->name);
        }
    }

    public function toLivewire(): array
    {
        $formatted = null;

        if ($this->formatted !== null) {
            $formatted = serialize(new Native($this->formatted));
        }

        return [
            'name' => $this->name,
            'label' => $this->label,
            'sortable' => $this->sortable,
            'defaultSort' => $this->defaultSort,
            'formatted' => $formatted,
        ];
    }

    public static function fromLivewire($value): self
    {
        $formatted = null;

        if ($value['formatted'] !== null) {
            /** @var Native $closure */
            $closure = unserialize(
                data: $value['formatted'],
                options: [
                    'allowed_classes' => [Native::class],
                ],
            );

            $formatted = $closure->getClosure();
        }

        return new self(
            name: $value['name'],
            label: $value['label'],
            sortable: $value['sortable'],
            defaultSort: $value['defaultSort'],
            formatted: $formatted,
        );
    }
}
