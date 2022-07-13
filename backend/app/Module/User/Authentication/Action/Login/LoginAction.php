<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Login;

use App\Infrastructure\ApiRequest\ResolveApiRequest;
use App\Infrastructure\ApiResponse\ResolveApiResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\User\Model\User;
use App\Module\User\Model\Users;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('api')]
final class LoginAction
{
    public function __construct(
        private readonly Users $users,
        private readonly Flusher $flusher,
        private readonly ResolveApiResponse $resolveApiResponse,
        private readonly ResolveApiRequest $resolveApiRequest,
        private readonly Hasher $hasher,
    ) {
    }

    #[Post('/auth/login')]
    public function __invoke(): JsonResponse
    {
        $loginRequest = ($this->resolveApiRequest)(LoginRequest::class);

        $user = $this->users->findByEmail($loginRequest->getEmail());

        if ($user === null /* || $this->hasher->check($credentials['password'], $user->getPassword()) */) {
            $user = new User($loginRequest->getEmail(), $loginRequest->getPassword());
            $this->users->add($user);
//            throw ApiException::createBadRequestException('Указан не верный логин или пароль', Error::BAD_REQUEST);
        }

        $token = $user->addToken();
        $this->flusher->flush();

        return ($this->resolveApiResponse)(new LoginResponse($token->getId(), $user->getEmail(), $user->getRoles()));
    }
}
