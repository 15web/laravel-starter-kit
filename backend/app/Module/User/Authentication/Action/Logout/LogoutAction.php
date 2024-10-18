<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Logout;

use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiException\Handler\Error;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Response\ResolveSuccessResponse;
use App\Module\User\Authentication\Model\Tokens;
use DomainException;
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
        private Tokens $tokens,
        private Flusher $flusher,
        private ResolveSuccessResponse $resolveSuccessResponse,
    ) {}

    #[Router\Post('/auth/logout')]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            throw ApiException::createUnauthorizedException('Необходимо пройти аутентификацию', Error::UNAUTHORIZED);
        }

        /** @var string $apiToken */
        $apiToken = $request->bearerToken();

        try {
            $token = $this->tokens->get($apiToken);

            $user->removeToken($token);
            $this->flusher->flush();
        } catch (DomainException|InvalidArgumentException $e) {
            throw ApiException::createUnexpectedException($e);
        }

        return ($this->resolveSuccessResponse)();
    }
}
