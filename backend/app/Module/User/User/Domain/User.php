<?php

declare(strict_types=1);

namespace App\Module\User\User\Domain;

use App\Infrastructure\ValueObject\Email;
use App\Module\User\Authentication\Domain\Token;
use App\Module\User\Authorization\Domain\Role;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Override;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

/**
 * Пользователь
 */
#[ORM\Entity, ORM\Table(name: 'users')]
/** @final */
class User implements Authenticatable
{
    #[ORM\Id, ORM\Column(type: Types::STRING, length: 36, unique: true)]
    private readonly string $id;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private string $password;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Token>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Token::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $tokens;

    /**
     * @param non-empty-string $password
     */
    public function __construct(
        Uuid $id,
        Email $email,
        string $password,
    ) {
        $this->id = (string) $id;
        $this->email = $email->value;
        $this->password = Hash::make($password);
        $this->roles = [Role::User->value];
        $this->createdAt = new DateTimeImmutable();
        $this->tokens = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return UuidV7::fromString($this->id);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function addToken(): Token
    {
        $token = new Token($this);
        $this->tokens->add($token);

        return $token;
    }

    public function removeToken(Token $token): void
    {
        if ($this->tokens->contains($token) === false) {
            throw new DomainException('Токен не найден');
        }

        $this->tokens->removeElement($token);
    }

    #[Override]
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    #[Override]
    public function getAuthIdentifier(): Uuid
    {
        return $this->getId();
    }

    #[Override]
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    #[Override]
    public function getRememberToken(): string
    {
        return '';
    }

    #[Override]
    public function setRememberToken($value): void {}

    #[Override]
    public function getRememberTokenName(): string
    {
        return '';
    }

    /**
     * @return array<Role>
     */
    public function getRoles(): array
    {
        return iterator_to_array(Role::fromStrings($this->roles));
    }

    public function hasRole(Role $role): bool
    {
        return \in_array($role, $this->getRoles(), true);
    }

    /**
     * @return non-empty-string
     */
    #[Override]
    public function getAuthPasswordName(): string
    {
        return 'password';
    }
}
