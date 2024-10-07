<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category\Create;

use App\Module\Products\Model\Category;
use DateTimeInterface;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class CategoryCreateResponse
{
    private int $id;
    private string $title;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(Category $category)
    {
        $this->id = $category->id;
        $this->title = $category->title;
        $this->createdAt = $category->created_at;
        $this->updatedAt = $category->updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
