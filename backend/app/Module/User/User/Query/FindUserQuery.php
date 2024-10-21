<?php

declare(strict_types=1);

namespace App\Module\User\User\Query;

use App\Infrastructure\ValueObject\Email;
use DomainException;
use SensitiveParameter;
use Webmozart\Assert\Assert;

/**
 * Запрос на нахождение данных пользователя
 */
final readonly class FindUserQuery
{
    public function __construct(
        public ?Email $email = null,
        #[SensitiveParameter]
        public ?string $authTokenId = null,
    ) {
        if ($email !== null) {
            return;
        }

        if ($authTokenId !== null) {
            Assert::uuid($authTokenId);

            return;
        }

        throw new DomainException();
    }
}
