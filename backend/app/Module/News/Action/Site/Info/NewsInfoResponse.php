<?php

declare(strict_types=1);

namespace App\Module\News\Action\Site\Info;

use App\Module\News\Model\News;
use DateTimeImmutable;

/**
 * Ответ для просмотра записи в новостях
 */
final readonly class NewsInfoResponse
{
    private int $id;
    private string $title;
    private DateTimeImmutable $createdAt;

    public function __construct(
        News $news,
    ) {
        $this->id = $news->getId();
        $this->title = $news->getTitle();
        $this->createdAt = $news->getCreatedAt();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
