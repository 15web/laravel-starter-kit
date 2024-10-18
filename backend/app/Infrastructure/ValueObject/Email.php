<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueObject;

use Webmozart\Assert\Assert;

/**
 * Email
 */
final readonly class Email
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        public string $value,
    ) {
        Assert::email($value);
    }
}
