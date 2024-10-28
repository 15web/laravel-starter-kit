<?php

declare(strict_types=1);

namespace App\User\Authentication\Domain;

use App\User\User\Domain\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Токен авторизации
 *
 * @final
 */
#[ORM\Entity, ORM\Table(name: 'user_tokens')]
class UserToken
{
    #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
    private readonly Uuid $id;

    #[ORM\Column]
    private readonly string $hash;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'tokens'), ORM\JoinColumn(nullable: false)]
        private User $user,
        AuthToken $token,
    ) {
        $this->id = $token->tokenId;
        $this->hash = $token->hash();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return non-empty-string
     */
    public function getHash(): string
    {
        /** @var non-empty-string $hash */
        $hash = $this->hash;

        return $hash;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
