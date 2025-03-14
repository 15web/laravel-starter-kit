<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Базовый компонент админки
 */
abstract class BaseComponent extends Component
{
    abstract public function header(): Header;

    abstract public function view(): View;

    final public function render(): View
    {
        return $this->view()
            ->layoutData([
                'header' => $this->header(),
            ]);
    }
}
