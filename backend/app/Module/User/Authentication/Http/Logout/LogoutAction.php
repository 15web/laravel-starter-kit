<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Http\Logout;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\ErrorCode;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\Module\User\Authentication\Domain\TokenRepository;
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
        private TokenRepository $tokens,
        private Flusher $flusher,
        private ResolveSuccessResponse $resolveSuccessResponse,
    ) {}

    #[Router\Post('/auth/logout')]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            throw ApiException::createUnauthorizedException('Необходимо пройти аутентификацию', ErrorCode::UNAUTHORIZED);
        }

        $tokenValue = (string) $request->bearerToken();
        $token = $this->tokens->find($tokenValue);

        if ($token === null) {
            throw ApiException::createUnauthorizedException('Необходимо пройти аутентификацию', ErrorCode::UNAUTHORIZED);
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
