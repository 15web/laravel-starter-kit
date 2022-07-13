<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\ByRole;

use App\Module\User\Model\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class DenyUnlessUserHasRole
{
    public function __construct(private readonly Request $request)
    {
    }

    /**
     * @throws AccessDeniedHttpException
     */
    public function __invoke(Role $role): void
    {
        /**
         * @var User $user
         */
        $user = $this->request->user();

        if ($user === null) {
            throw new AccessDeniedHttpException();
        }

        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException();
        }
    }
}
