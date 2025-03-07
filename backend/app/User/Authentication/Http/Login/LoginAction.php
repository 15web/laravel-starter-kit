<?php

declare(strict_types=1);

namespace App\User\Authentication\Http\Login;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\RateLimiter\RateLimiter;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use App\Infrastructure\ValueObject\Email;
use App\User\Authentication\Domain\AuthToken;
use App\User\Authentication\Service\TokenUserProvider;
use App\User\Authorization\Domain\Role;
use App\User\User\Domain\IncorrectPassword;
use App\User\User\Domain\User;
use App\User\User\Query\FindUser;
use App\User\User\Query\FindUserQuery;
use App\User\User\Service\CreateUser;
use App\User\User\Service\CreateUserCommand;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes as Router;
use Symfony\Component\Uid\UuidV7;

/**
 * Ручка для входа
 */
final readonly class LoginAction
{
    public function __construct(
        private FindUser $findUser,
        private CreateUser $createUser,
        private Flusher $flusher,
        private ResolveResponse $resolveResponse,
        private ResolveRequestBody $resolveRequest,
        private TokenUserProvider $auth,
    ) {}

    #[Router\Post('/auth/login')]
    public function __invoke(Request $request): JsonResponse
    {
        /** @var non-empty-string $clientIp */
        $clientIp = $request->getClientIp();

        /** @var positive-int $maxAttempts */
        $maxAttempts = config('auth.rate_limiter_max_attempts.login');

        $rateLimiter = new RateLimiter(
            key: $clientIp,
            maxAttempts: $maxAttempts,
        );

        if ($rateLimiter->isExceeded()) {
            throw ApiException::createTooManyRequestsException(
                retryAfter: $rateLimiter->availableIn(),
                limit: $rateLimiter->getMaxAttempts(),
            );
        }

        $rateLimiter->increment();

        $loginRequest = ($this->resolveRequest)(LoginRequest::class);
        $user = $this->getOrRegisterUser($loginRequest);

        $authToken = AuthToken::generate();
        $user->addToken($authToken);
        $this->flusher->flush();

        $rateLimiter->clear();

        /** @var list<Role> $roles */
        $roles = $user->getRoles();

        return ($this->resolveResponse)(
            new ApiObjectResponse(
                new LoginResponse(
                    token: (string) $authToken,
                    email: $user->getEmail(),
                    roles: $roles,
                ),
            ),
        );
    }

    private function getOrRegisterUser(LoginRequest $request): User
    {
        $query = new FindUserQuery(
            email: new Email($request->email),
        );

        $user = ($this->findUser)($query);

        if ($user === null) {
            return $this->registerUser($request);
        }

        try {
            $user->checkPassword($request->password);

            $this->auth->rehashPasswordIfRequired(
                user: $user,
                credentials: ['password' => $request->password],
            );
        } catch (IncorrectPassword) {
            throw ApiException::createUnauthenticatedException('Некорректный логин или пароль');
        }

        return $user;
    }

    private function registerUser(LoginRequest $request): User
    {
        try {
            $user = ($this->createUser)(
                new CreateUserCommand(
                    id: new UuidV7(),
                    email: new Email($request->email),
                    password: $request->password,
                ),
            );
        } catch (DomainException $e) {
            throw ApiException::createUnexpectedException($e);
        }

        return $user;
    }
}
