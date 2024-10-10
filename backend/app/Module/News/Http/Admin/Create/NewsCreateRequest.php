<?php

declare(strict_types=1);

namespace App\Module\News\Http\Admin\Create;

use App\Infrastructure\Request\Request;

/**
 * Запрос создания записи в новостях
 */
final readonly class NewsCreateRequest implements Request
{
    public function __construct(
        private string $title,
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }
}
