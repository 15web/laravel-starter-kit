<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Create;

use App\Module\Blog\Model\Post;
use DateTimeInterface;

/**
 * Результат создания записи в блоге
 */
final readonly class PostCreateResponse
{
    private int $id;
    private string $title;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(Post $post)
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->createdAt = $post->created_at;
        $this->updatedAt = $post->updated_at;
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
