<?php

declare(strict_types=1);

namespace App\User\User\Service;

use App\Infrastructure\Doctrine\Flusher;
use App\User\User\Domain\User;
use App\User\User\Query\FindUser;
use App\User\User\Query\FindUserQuery;
use App\User\User\Service\Exception\UserExists;
use Doctrine\ORM\EntityManager;
use DomainException;

/**
 * Создает нового пользователя
 */
final readonly class CreateUser
{
    public function __construct(
        private FindUser $findUser,
        private EntityManager $entityManager,
        private Flusher $flusher,
    ) {}

    public function __invoke(CreateUserCommand $command): User
    {
        $userData = ($this->findUser)(
            new FindUserQuery(
                email: $command->email,
            )
        );

        if ($userData !== null) {
            throw new UserExists();
        }

        $user = new User(
            id: $command->id,
            email: $command->email,
            password: $command->password,
        );

        $this->entityManager->persist($user);

        $this->flusher->flush();

        $createdUser = ($this->findUser)(
            new FindUserQuery(
                email: $command->email,
            )
        );

        if ($createdUser === null) {
            throw new DomainException();
        }

        return $createdUser;
    }
}
