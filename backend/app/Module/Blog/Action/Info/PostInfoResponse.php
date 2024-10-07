<?php

declare(strict_types=1);

namespace App\Module\Blog\Action\Info;

use App\Module\Blog\Model\Post;
use DateTimeInterface;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class PostInfoResponse
{
    private int $id;
    private string $title;
    private string $author;
    private string $content;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        Post $post,
    ) {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->author = $post->author;
        $this->content = $post->content;
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

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getContent(): string
    {
        return $this->content;
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
