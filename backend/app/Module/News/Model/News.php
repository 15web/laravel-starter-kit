<?php

declare(strict_types=1);

namespace App\Module\News\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
/** @final */
class News
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column]
    private string $title;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
