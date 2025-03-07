<?php

declare(strict_types=1);

namespace App\Ping\Http;

use App\Infrastructure\Response\ApiObjectResponse;
use App\Infrastructure\Response\ResolveResponse;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes as Router;

/**
 * Ручка пинга
 */
final readonly class PingAction
{
    public function __construct(
        private ResolveResponse $resolveResponse,
        private EntityManager $entityManager,
    ) {}

    #[Router\Get('/ping')]
    public function __invoke(): JsonResponse
    {
        /** @var non-empty-string $result */
        $result = $this->entityManager->getConnection()->fetchOne("select 'pong'");

        return ($this->resolveResponse)(
            new ApiObjectResponse(
                data: new Pong(
                    result: $result,
                    now: new DateTimeImmutable(),
                ),
            ),
        );
    }
}
