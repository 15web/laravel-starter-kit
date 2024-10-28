<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Override;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Ответ со списком и пагинацией
 */
final readonly class ApiListObjectResponse implements ApiResponse
{
    /**
     * @param iterable<object> $data
     */
    public function __construct(
        public iterable $data,
        public ?PaginationResponse $pagination = null,
        public ResponseStatus $status = ResponseStatus::Success,
    ) {}

    /**
     * @param int $options
     */
    #[Override]
    public function toJson($options = JSON_THROW_ON_ERROR): string
    {
        return app()->make(Serializer::class)->serialize(
            data: $this,
            format: JsonEncoder::FORMAT,
        );
    }
}
