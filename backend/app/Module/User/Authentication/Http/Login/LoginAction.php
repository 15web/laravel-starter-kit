<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Http\Login;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Request\ResolveRequest;
use App\Infrastructure\Response\ResolveResponse;
use App\Infrastructure\ValueObject\Email;
use App\Module\User\Authorization\Domain\Role;
use App\Module\User\User\Query\FindUser;
use App\Module\User\User\Query\FindUserQuery;
use App\Module\User\User\Service\CreateUser;
use App\Module\User\User\Service\CreateUserCommand;
use DomainException;
use Illuminate\Http\JsonResponse;
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
        private ResolveRequest $resolveRequest,
    ) {}

    #[Router\Post('/auth/login')]
    public function __invoke(): JsonResponse
    {
        $request = ($this->resolveRequest)(LoginRequest::class);

        $query = new FindUserQuery(
            email: new Email($request->email),
        );

        $user = ($this->findUser)($query);

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

        $token = $user->addToken();
        $this->flusher->flush();

        /** @var list<Role> $roles */
        $roles = $user->getRoles();

        return ($this->resolveResponse)(
            new LoginResponse(
                token: (string) $token->getId(),
                email: $user->getEmail(),
                roles: $roles,
            )
        );
    }
}
