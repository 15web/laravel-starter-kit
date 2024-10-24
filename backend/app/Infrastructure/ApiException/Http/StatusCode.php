<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Http;

/**
 * Код HTTP статуса
 */
enum StatusCode: int
{
    case OK = 200;

    case BAD_REQUEST = 400;

    case UNAUTHENTICATED = 401;

    case FORBIDDEN = 403;

    case NOT_FOUND = 404;

    case METHOD_NOT_ALLOWED = 405;

    case SERVER_ERROR = 500;
}
