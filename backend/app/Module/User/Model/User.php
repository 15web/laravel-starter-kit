<?php

declare(strict_types=1);

namespace App\Module\User\Model;

use App\Module\User\Authentication\Model\Token;
use App\Module\User\Authorization\ByRole\Role;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Override;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
#[ORM\Entity, ORM\Table(name: 'users')]
/** @final */
class User implements Authenticatable
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?string $password;

    /**
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    private array $roles;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Token>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Token::class, cascade: ['all'], orphanRemoval: true)]
    private Collection $tokens;

    public function __construct(#[ORM\Column]
        private string $email, string $password)
    {
        $this->password = Hash::make($password);
        $this->roles = [Role::User->value];
        $this->createdAt = new DateTimeImmutable();
        $this->tokens = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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
    public function getAuthIdentifier(): int
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

    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
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
