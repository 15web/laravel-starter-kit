<?php

declare(strict_types=1);

namespace App\Module\News\Action\Admin\Create;

use App\Infrastructure\ApiRequest\ApiRequest;

/**
 * Запрос создания записи в новостях
 */
final readonly class NewsCreateRequest implements ApiRequest
{
    public function __construct(
        private string $title,
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }
}
