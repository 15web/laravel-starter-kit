<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\Http;

use App\Module\User\Authorization\Domain\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Запрещает входить без роли
 */
final readonly class CheckRoleGranted
{
    public function __construct(
        private Request $request,
    ) {}

    /**
     * @throws AccessDeniedHttpException
     */
    public function __invoke(Role $role): void
    {
        $user = $this->request->user();

        if ($user === null) {
            throw new AccessDeniedHttpException();
        }

        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException();
        }
    }
}
