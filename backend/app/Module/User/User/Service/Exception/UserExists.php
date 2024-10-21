<?php

declare(strict_types=1);

namespace App\Module\User\User\Service\Exception;

use Exception;

/**
 * Пользователь с таким email уже существует
 */
final class UserExists extends Exception {}
