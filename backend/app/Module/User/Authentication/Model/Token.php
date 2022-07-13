<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Model;

use App\Module\User\Model\User;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Str;

#[ORM\Entity]
/** @final */
class Token
{
    #[ORM\Id, ORM\Column(unique: true)]
    private string $id;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'tokens'), ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(User $user)
    {
        $this->id = (string) Str::uuid();
        $this->user = $user;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
