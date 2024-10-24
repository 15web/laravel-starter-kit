<?php

declare(strict_types=1);

namespace App\Module\User\User\Query;

use App\Infrastructure\ValueObject\Email;
use DomainException;
use SensitiveParameter;

/**
 * Запрос на нахождение данных пользователя
 */
final readonly class FindUserQuery
{
    /**
     * @param non-empty-string|null $authToken
     */
    public function __construct(
        public ?Email $email = null,
        #[SensitiveParameter]
        public ?string $authToken = null,
    ) {
        if ($email !== null) {
            return;
        }

        if ($authToken !== null) {
            return;
        }

        throw new DomainException();
    }
}
