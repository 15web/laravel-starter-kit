<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException\Handler;

/**
 * Код ошибки API
 */
enum ErrorCode: string
{
    case BAD_REQUEST = 'bad_request';

    case UNAUTHENTICATED = 'unauthenticated';

    case FORBIDDEN = 'forbidden';

    case NOT_FOUND = 'not_found';

    case METHOD_NOT_ALLOWED = 'method_not_allowed';

    case UNEXPECTED = 'unexpected';

    case TOO_MANY_REQUESTS = 'too_many_requests';

    case EXISTS = 'entity_exists';
}
