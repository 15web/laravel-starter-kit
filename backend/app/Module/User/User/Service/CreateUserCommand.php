<?php

declare(strict_types=1);

namespace App\Module\User\User\Service;

use App\Infrastructure\ValueObject\Email;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * Команда на создание пользователя
 */
final readonly class CreateUserCommand
{
    /**
     * @param Uuid $id Id пользователя
     * @param Email $email Email
     * @param non-empty-string $password Пароль
     */
    public function __construct(
        public Uuid $id,
        public Email $email,
        public string $password,
    ) {
        Assert::notEmpty($password);
    }
}
