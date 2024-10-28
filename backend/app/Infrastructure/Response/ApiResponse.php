<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use Illuminate\Contracts\Support\Jsonable;

/**
 * Интерфейс для ответов API
 */
interface ApiResponse extends Jsonable {}
