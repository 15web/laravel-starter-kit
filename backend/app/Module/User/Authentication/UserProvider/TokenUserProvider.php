<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\UserProvider;

use App\Module\User\Model\User;
use App\Module\User\Model\Users;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use SensitiveParameter;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class TokenUserProvider implements UserProvider
{
    public function __construct(private Users $users) {}

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param mixed $identifier
     */
    public function retrieveById($identifier): ?User
    {
        return null;
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param mixed $identifier
     * @param mixed $token
     */
    public function retrieveByToken($identifier, $token): ?User
    {
        return null;
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @param mixed $token
     */
    public function updateRememberToken(Authenticatable $user, $token): void {}

    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
     * $credentials['api_token'] @see \Illuminate\Auth\TokenGuard::user
     */
    public function retrieveByCredentials(array $credentials): ?User
    {
        return $this->users->findByToken($credentials['api_token']);
    }

    /**
     * Используется только в \Illuminate\Auth\SessionGuard
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[SensitiveParameter] array $credentials, bool $force = false): void
    {
        /*
         * @todo: Implement rehashPasswordIfRequired() method.
         * @see \Illuminate\Auth\DatabaseUserProvider::rehashPasswordIfRequired
         */
    }
}
