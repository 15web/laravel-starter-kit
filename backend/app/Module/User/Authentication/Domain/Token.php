<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Domain;

use App\Module\User\User\Domain\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Токен авторизации
 */
#[ORM\Entity, ORM\Table(name: 'user_tokens')]
/** @final */
class Token
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly string $id;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'tokens'), ORM\JoinColumn(nullable: false)]
        private User $user,
    ) {
        $this->id = (string) Uuid::v7();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
