<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\User\Model\User;
use App\Module\User\Model\Users;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

#[Router\Prefix('api')]
final class LoginAction
{
    public function __construct(
        private readonly Users $users,
        private readonly Flusher $flusher,
        private readonly ResolveApiResponse $resolveApiResponse,
        private readonly ResolveApiRequest $resolveApiRequest,
    ) {
    }

    #[Router\Post('/auth/login')]
    public function __invoke(): JsonResponse
    {
        $loginRequest = ($this->resolveApiRequest)(LoginRequest::class);

        $user = $this->users->findByEmail($loginRequest->getEmail());

        if ($user === null) {
            $user = new User($loginRequest->getEmail(), $loginRequest->getPassword());
            $this->users->add($user);
        }

        $token = $user->addToken();
        $this->flusher->flush();

        return ($this->resolveApiResponse)(new LoginResponse($token->getId(), $user->getEmail(), $user->getRoles()));
    }
}
