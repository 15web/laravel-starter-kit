<?php

declare(strict_types=1);

namespace App\Module\Products\Action\Category;

final class CategoryTreeResponse
{
    public function __construct(
        private int $id,
        private string $title,
        private \Iterator $children,
        private \DateTimeInterface $createdAt,
        private \DateTimeInterface $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getChildren(): \Iterator
    {
        return $this->children;
    }
}
