<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\ByRole;

use App\Module\User\Model\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
final readonly class DenyUnlessUserHasRole
{
    public function __construct(private Request $request) {}

    /**
     * @throws AccessDeniedHttpException
     */
    public function __invoke(Role $role): void
    {
        /** @var User|null $user */
        $user = $this->request->user();

        if ($user === null) {
            throw new AccessDeniedHttpException();
        }

        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException();
        }
    }
}
