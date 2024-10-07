<?php

declare(strict_types=1);

namespace App\Module\News\Model;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Новость
 *
 * @final
 */
#[ORM\Entity, ORM\Table(name: 'news')]
class News
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private int $id;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\Column]
        private string $title,
    ) {
        $this->createdAt = new DateTimeImmutable();
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
