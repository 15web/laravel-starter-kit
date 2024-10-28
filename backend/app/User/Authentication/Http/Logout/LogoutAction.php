<?php

declare(strict_types=1);

namespace App\User\Authentication\Http\Logout;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\User\Authentication\Domain\AuthToken;
use App\User\Authentication\Domain\UserTokenRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка для выхода
 */
#[Router\Middleware('auth')]
final readonly class LogoutAction
{
    public function __construct(
        private UserTokenRepository $tokens,
        private Flusher $flusher,
        private ResolveSuccessResponse $resolveSuccessResponse,
    ) {}

    #[Router\Post('/auth/logout')]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            throw ApiException::createUnauthenticatedException('Необходимо пройти аутентификацию');
        }

        $authToken = AuthToken::createFromString(
            (string) $request->bearerToken(),
        );

        $token = $this->tokens->find($authToken->tokenId);

        if ($token === null) {
            throw ApiException::createUnauthenticatedException('Необходимо пройти аутентификацию');
        }

        try {
            $user->removeToken($token);
            $this->flusher->flush();
        } catch (InvalidArgumentException $e) {
            throw ApiException::createUnexpectedException($e);
        }

        return ($this->resolveSuccessResponse)();
    }
}
