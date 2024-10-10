<?php

declare(strict_types=1);

namespace App\Module\News\Http\Site\Info;

use App\Infrastructure\Request\Request;

/**
 * Запрос для просмотра записи в новостях
 */
final readonly class NewsInfoRequest implements Request
{
    public function __construct(private string $title) {}

    public function getTitle(): string
    {
        return $this->title;
    }
}
