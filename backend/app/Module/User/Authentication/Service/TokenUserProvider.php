<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Service;

use App\Module\User\User\Domain\User;
use App\Module\User\User\Query\FindUser;
use App\Module\User\User\Query\FindUserQuery;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Override;
use SensitiveParameter;

/**
 * Провайдер для реализации аутентификации пользователя
 */
final readonly class TokenUserProvider implements UserProvider
{
    /**
     * @see \Illuminate\Auth\TokenGuard::$storageKey
     * @see \Illuminate\Auth\TokenGuard::user
     */
    private const string CREDENTIALS_TOKEN_KEY = 'api_token';

    public function __construct(
        private FindUser $findUser,
    ) {}

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     *
     * @param mixed $identifier
     */
    #[Override]
    public function retrieveById($identifier): ?User
    {
        return null;
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     *
     * @param mixed $identifier
     * @param mixed $token
     */
    #[Override]
    public function retrieveByToken($identifier, $token): ?User
    {
        return null;
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     *
     * @param mixed $token
     */
    #[Override]
    public function updateRememberToken(Authenticatable $user, $token): void {}

    #[Override]
    public function retrieveByCredentials(array $credentials): ?User
    {
        /** @var string $token */
        $token = $credentials[self::CREDENTIALS_TOKEN_KEY];

        $query = new FindUserQuery(
            authTokenId: $token,
        );

        return ($this->findUser)($query);
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     */
    #[Override]
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    #[Override]
    public function rehashPasswordIfRequired(Authenticatable $user, #[SensitiveParameter] array $credentials, bool $force = false): void
    {
        /*
         * @todo: Implement rehashPasswordIfRequired() method.
         * @see \Illuminate\Auth\DatabaseUserProvider::rehashPasswordIfRequired
         */
    }
}
