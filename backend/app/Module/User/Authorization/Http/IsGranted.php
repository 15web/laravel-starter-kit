<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\Http;

use App\Module\User\Authorization\Domain\Role;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class IsGranted
{
    public function __construct(
        public Role $role,
    ) {}
}
