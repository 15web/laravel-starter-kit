<?php

declare(strict_types=1);

namespace App\User\User\Domain;

use App\Infrastructure\ValueObject\Email;
use App\User\Authentication\Domain\AuthToken;
use App\User\Authentication\Domain\UserToken;
use App\User\Authorization\Domain\Role;
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

/**
 * Пользователь
 *
 * @final
 */
#[ORM\Entity, ORM\Table(name: 'users')]
class User implements Authenticatable
{
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
     * @var Collection<int, UserToken>
     */
    #[ORM\OneToMany(targetEntity: UserToken::class, mappedBy: 'user', cascade: ['all'], orphanRemoval: true)]
    private Collection $tokens;

    /**
     * @param non-empty-string $password
     */
    public function __construct(
        #[ORM\Id, ORM\Column(type: 'uuid', unique: true)]
        private readonly Uuid $id,
        Email $email,
        string $password,
    ) {
        $this->email = $email->value;
        $this->password = Hash::make($password);
        $this->roles = [Role::User->value];
        $this->createdAt = new DateTimeImmutable();
        $this->tokens = new ArrayCollection();
    }

    public function getId(): Uuid
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function addToken(AuthToken $token): void
    {
        $userToken = new UserToken(
            user: $this,
            token: $token,
        );

        $this->tokens->add($userToken);
    }

    public function removeToken(UserToken $token): void
    {
        if ($this->tokens->contains($token) === false) {
            throw new DomainException('Токен не найден');
        }

        $this->tokens->removeElement($token);
    }

    /**
     * @param non-empty-string $password
     */
    public function checkPassword(string $password): void
    {
        $valid = Hash::check($password, $this->password);

        if (!$valid) {
            throw new IncorrectPassword();
        }
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
        return $this->getPassword();
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

    public function rehashPassword(string $password): void
    {
        $this->password = $password;
    }
}
