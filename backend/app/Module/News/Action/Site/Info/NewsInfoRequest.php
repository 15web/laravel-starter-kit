<?php

declare(strict_types=1);

namespace App\Module\News\Action\Site\Info;

use App\Infrastructure\ApiRequest\ApiRequest;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class NewsInfoRequest implements ApiRequest
{
    public function __construct(private string $title) {}

    public function getTitle(): string
    {
        return $this->title;
    }
}
