<?php

declare(strict_types=1);

namespace App\User\Authentication\Http\Login;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequestBody;
use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ApiResponse;
use App\Infrastructure\ValueObject\Email;
use App\User\Authentication\Domain\AuthToken;
use App\User\Authentication\Service\TokenUserProvider;
use App\User\Authorization\Domain\Role;
use App\User\User\Domain\IncorrectPassword;
use App\User\User\Query\FindUser;
use App\User\User\Query\FindUserQuery;
use App\User\User\Service\CreateUser;
use App\User\User\Service\CreateUserCommand;
use DomainException;
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
        private ResolveRequestBody $resolveRequest,
        private TokenUserProvider $auth,
    ) {}

    #[Router\Post('/auth/login')]
    public function __invoke(): ApiResponse
    {
        $request = ($this->resolveRequest)(LoginRequest::class);

        $query = new FindUserQuery(
            email: new Email($request->email),
        );

        $user = ($this->findUser)($query);

        if ($user !== null) {
            try {
                $user->checkPassword($request->password);

                $this->auth->rehashPasswordIfRequired(
                    user: $user,
                    credentials: ['password' => $request->password],
                );
            } catch (IncorrectPassword) {
                throw ApiException::createUnauthenticatedException('Некорректный логин или пароль');
            }
        }

        if ($user === null) {
            try {
                $user = ($this->createUser)(
                    new CreateUserCommand(
                        id: new UuidV7(),
                        email: new Email($request->email),
                        password: $request->password,
                    )
                );
            } catch (DomainException $e) {
                throw ApiException::createUnexpectedException($e);
            }
        }

        $authToken = AuthToken::generate();

        $user->addToken($authToken);
        $this->flusher->flush();

        /** @var list<Role> $roles */
        $roles = $user->getRoles();

        return new ApiObjectResponse(
            new LoginResponse(
                token: (string) $authToken,
                email: $user->getEmail(),
                roles: $roles,
            ),
        );
    }
}
