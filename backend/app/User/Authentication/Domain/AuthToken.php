<?php

declare(strict_types=1);

namespace App\User\Authentication\Domain;

use DomainException;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Override;
use SensitiveParameter;
use Stringable;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

/**
 * Токен аутентификации
 */
final readonly class AuthToken implements Stringable
{
    /**
     * @param Uuid $tokenId Идентификатор токена
     * @param Uuid $token Токен
     */
    public function __construct(
        public Uuid $tokenId,
        #[SensitiveParameter]
        public Uuid $token,
    ) {}

    /**
     * @return non-empty-string
     */
    #[Override]
    public function __toString(): string
    {
        return \sprintf('%s-%s', $this->tokenId, $this->token);
    }

    public static function generate(): self
    {
        return new self(
            tokenId: new UuidV7(),
            token: new UuidV7(),
        );
    }

    public static function createFromString(string $token): self
    {
        $tokenId = substr($token, 0, 36);
        $tokenBody = substr($token, 37);

        try {
            return new self(
                tokenId: Uuid::fromString($tokenId),
                token: Uuid::fromString($tokenBody),
            );
        } catch (InvalidArgumentException) {
            throw new DomainException();
        }
    }

    /**
     * @return non-empty-string
     */
    public function hash(): string
    {
        /** @var non-empty-string $hash */
        $hash = Hash::make(
            (string) $this->token,
        );

        return $hash;
    }

    public function verify(UserToken $token): bool
    {
        return Hash::check(
            value: (string) $this->token,
            hashedValue: $token->getHash(),
        );
    }
}
