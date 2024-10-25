<?php

declare(strict_types=1);

namespace App\News\Http\Site\Subscribe;

use App\Infrastructure\Request\Request;
use Webmozart\Assert\Assert;

/**
 * Запрос на подписку на новости
 */
final readonly class SubscribeRequest implements Request
{
    /**
     * @param non-empty-string $email
     */
    public function __construct(
        public string $email,
    ) {
        Assert::email($email);
    }
}
