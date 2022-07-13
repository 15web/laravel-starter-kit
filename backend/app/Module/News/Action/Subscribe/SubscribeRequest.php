<?php

declare(strict_types=1);

namespace App\Module\News\Action\Subscribe;

use App\Infrastructure\ApiRequest\ApiRequest;
use Webmozart\Assert\Assert;

final class SubscribeRequest implements ApiRequest
{
    public function __construct(
        private string $email,
    ) {
        Assert::email($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
