<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Module\User\Model\User;
use App\Module\User\Model\Users;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка для входа
 */
final readonly class LoginAction
{
    public function __construct(
        private Users $users,
        private Flusher $flusher,
        private ResolveResponse $resolveResponse,
        private ResolveRequest $resolveRequest,
    ) {}

    #[Router\Post('/auth/login')]
    public function __invoke(): JsonResponse
    {
        $loginRequest = ($this->resolveRequest)(LoginRequest::class);

        $user = $this->users->findByEmail($loginRequest->getEmail());

        if ($user === null) {
            $user = new User($loginRequest->getEmail(), $loginRequest->getPassword());
            $this->users->add($user);
        }

        $token = $user->addToken();
        $this->flusher->flush();

        return ($this->resolveResponse)(new LoginResponse($token->getId(), $user->getEmail(), $user->getRoles()));
    }
}
