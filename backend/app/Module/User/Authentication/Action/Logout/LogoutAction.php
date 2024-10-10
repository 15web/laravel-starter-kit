<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action\Logout;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiResponse\ResolveSuccessResponse;
use App\Infrastructure\Doctrine\Flusher;
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

    #[Router\Get('/auth/logout')]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user === null) {
            throw new InvalidArgumentException();
        }

        /** @var string $apiToken */
        $apiToken = $request->bearerToken();

        try {
            $token = $this->tokens->get($apiToken);

            $user->removeToken($token);
            $this->flusher->flush();
        } catch (DomainException|InvalidArgumentException $e) {
            throw ApiException::createBadRequestException($e->getMessage(), Error::BAD_REQUEST);
        }

        return ($this->resolveSuccessResponse)();
    }
}
