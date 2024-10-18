<?php

namespace App\Module\User\Authorization\Http;

use App\Module\User\Authorization\Domain\Role;
use App\Module\User\User\Domain\User;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckRoleGrantedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dd($request->user());

        $route = Route::getRoutes()->match($request);

        $action = $route->getControllerClass();
        $reflection = new ReflectionClass($action);

        foreach ($reflection->getAttributes() as $attribute) {
            if ($attribute->getName() === IsGranted::class) {
                /** @var array{0: Role} $arguments */
                $arguments = $attribute->getArguments();

                $this->checkRoleGranted(
                    role: $arguments[0],
                    user: $request->user(),
                );
                break;
            }
        }

        return $next($request);
    }

    private function checkRoleGranted(Role $role, ?User $user): void
    {
        dd($user, $role);

        if ($user === null) {
            throw new AccessDeniedHttpException();
        }


        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException();
        }
    }
}
