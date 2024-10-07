<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Model;

use App\Module\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Str;

/**
 * Токен авторизации
 */
#[ORM\Entity, ORM\Table(name: 'user_tokens')]
/** @final */
class Token
{
    #[ORM\Id, ORM\Column(unique: true, length: 36)]
    private string $id;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(#[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'tokens'), ORM\JoinColumn(nullable: false)]
        private User $user)
    {
        $this->id = (string) Str::uuid();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
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
