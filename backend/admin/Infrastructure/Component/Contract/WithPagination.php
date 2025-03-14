<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Contract;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;

/**
 * В компоненте используется пагинация
 */
trait WithPagination
{
    #[Url(history: true)]
    public int $offset = 0;

    #[Url(history: true), Session(key: 'limit-'.self::class)]
    public int $limit = 10;

    #[Computed]
    public function currentOffset(): int
    {
        return $this->offset + 1;
    }

    #[Computed]
    public function maxOffset(): int
    {
        return min($this->offset + $this->limit, $this->entityTotal);
    }

    public function updatedWithPagination($property): void
    {
        if ($property === 'limit') {
            $this->reset('offset');
        }
    }
}
