<?php

declare(strict_types=1);

namespace App\Module\User\Authentication\Action;

use App\Contract\Error;
use App\Infrastructure\ApiException\ApiException;
use App\Infrastructure\ApiResponse\ResolveSuccessResponse;
use App\Infrastructure\Doctrine\Flusher;
use App\Module\User\Authentication\Model\Tokens;
use App\Module\User\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes  as Router;

#[Router\Prefix('api')]
#[Router\Middleware('auth')]
final class LogoutAction
{
    public function __construct(
        private readonly Tokens $tokens,
        private readonly Flusher $flusher,
        private readonly ResolveSuccessResponse $resolveSuccessResponse,
    ) {
    }

    #[Router\Get('/auth/logout')]
    public function __invoke(Request $request): JsonResponse
    {
        /**
         * @var ?User $user
         */
        $user = $request->user();
        if ($user === null) {
            throw new \InvalidArgumentException();
        }

        /** @var string $apiToken */
        $apiToken = $request->bearerToken();

        try {
            $token = $this->tokens->get($apiToken);

            $user->removeToken($token);
            $this->flusher->flush();
        } catch (\DomainException|\InvalidArgumentException $e) {
            throw ApiException::createBadRequestException($e->getMessage(), Error::BAD_REQUEST);
        }

        return ($this->resolveSuccessResponse)();
    }
}
