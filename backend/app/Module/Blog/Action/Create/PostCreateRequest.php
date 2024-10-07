<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Create;

use App\Infrastructure\ApiRequest\ApiRequest;

/**
 * Запрос для создания записи в блоге
 */
final readonly class PostCreateRequest implements ApiRequest
{
    public function __construct(
        private string $title,
        private string $author,
        private string $content,
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
