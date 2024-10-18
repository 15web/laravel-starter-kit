<?php

declare(strict_types=1);

namespace App\Module\User\Authorization\Http;

use App\Module\User\Authorization\Domain\Role;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Ограничивает доступ к ресурсу по роли
 */
final readonly class CheckRoleGrantedMiddleware
{
    public function __construct(
        private AuthManager $authManager,
    ) {}

    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = Route::getRoutes()->match($request);

        /** @var class-string $action */
        $action = $route->getControllerClass();
        $reflection = new ReflectionClass($action);

        foreach ($reflection->getAttributes() as $attribute) {
            if ($attribute->getName() === IsGranted::class) {
                /** @var array{0: Role} $arguments */
                $arguments = $attribute->getArguments();

                $this->checkRoleGranted(
                    role: $arguments[0],
                );

                break;
            }
        }

        return $next($request);
    }

    private function checkRoleGranted(Role $role): void
    {
        $user = $this->authManager->user();

        if ($user === null) {
            throw new AuthenticationException();
        }

        if (!$user->hasRole($role)) {
            throw new AccessDeniedHttpException();
        }
    }
}
