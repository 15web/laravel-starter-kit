<?php

declare(strict_types=1);

namespace App\Module\User\User\Service;

use App\Infrastructure\Doctrine\Flusher;
use App\Module\User\User\Domain\User;
use App\Module\User\User\Query\FindUser;
use App\Module\User\User\Query\FindUserQuery;
use App\Module\User\User\Service\Exception\UserExists;
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
