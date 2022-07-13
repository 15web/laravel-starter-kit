<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\ByRole;

enum Role: string
{
    case Admin = 'admin';

    case User = 'user';

    /**
     * @param string[] $strings
     *
     * @return \Generator<self>
     */
    public static function fromStrings(array $strings): \Generator
    {
        foreach ($strings as $string) {
            yield self::from($string);
        }
    }
}
