<?php

declare(strict_types=1);

namespace App\Contract;

/**
 * Код ошибки API
 */
enum Error: string
{
    case BAD_REQUEST = 'general_bad_request';

    case UNAUTHORIZED = 'general_unauthorized';

    case ACCESS_DENIED = 'general_access_denied';

    case NOT_FOUND = 'general_not_found';

    case METHOD_NOT_ALLOWED = 'general_method_not_allowed';

    case UNEXPECTED = 'general_unexpected';

    case EXISTS = 'entity_exists';

    case NEWS_NOT_FOUND = 'news_not_found';

    case NEWS_EXISTS = 'news_exists';
}
