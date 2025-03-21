<?php

declare(strict_types=1);

namespace App\User\Authentication\Http\Login;

use App\Infrastructure\Request\Request;
use SensitiveParameter;
use Webmozart\Assert\Assert;

/**
 * Запрос на вход
 */
final readonly class LoginRequest implements Request
{
    /**
     * @param non-empty-string $email Email
     * @param non-empty-string $password Пароль
     */
    public function __construct(
        public string $email,
        #[SensitiveParameter]
        public string $password,
    ) {
        Assert::email($email, "Значение '{$email}' должно быть действительным электронным адресом.");
    }
}
