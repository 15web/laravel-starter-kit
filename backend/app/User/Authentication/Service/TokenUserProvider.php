<?php

declare(strict_types=1);

namespace App\User\Authentication\Service;

use App\User\Authentication\Domain\AuthToken;
use App\User\Authentication\Domain\UserTokenRepository;
use App\User\User\Domain\User;
use DomainException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
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
        private UserTokenRepository $repository,
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
        /** @var string $tokenValue */
        $tokenValue = $credentials[self::CREDENTIALS_TOKEN_KEY];

        try {
            $authToken = AuthToken::createFromString($tokenValue);
        } catch (DomainException) {
            return null;
        }

        $userToken = $this->repository->find($authToken->tokenId);

        if ($userToken === null) {
            return null;
        }

        if (!$authToken->verify($userToken)) {
            return null;
        }

        return $userToken->getUser();
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
        if (!Hash::needsRehash($user->getAuthPassword()) && !$force) {
            return;
        }

        /**
         * @var User $user
         * @var array{password: non-empty-string} $credentials
         */
        $user->rehashPassword(
            Hash::make($credentials['password']),
        );
    }
}
