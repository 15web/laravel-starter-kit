<?php

declare(strict_types=1);

namespace App\Infrastructure\Request;

use CuyZ\Valinor\Mapper\Source\JsonSource;
use CuyZ\Valinor\Mapper\TreeMapper;
use Illuminate\Support\Facades\Request as CurrentRequest;

/**
 * Денормализует запрос (body) в объект
 */
final readonly class ResolveRequestBody
{
    public function __construct(
        private TreeMapper $mapper,
    ) {}

    /**
     * @template T of Request
     *
     * @param class-string<T> $className
     *
     * @return T
     */
    public function __invoke(string $className): Request
    {
        $rawJson = (string) CurrentRequest::getContent();

        return $this->mapper->map(
            signature: $className,
            source: new JsonSource($rawJson),
        );
    }
}
