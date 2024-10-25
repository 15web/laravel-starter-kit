<?php

declare(strict_types=1);

namespace App\User\User\Domain;

use Exception;

/**
 * Неправильный пароль
 */
final class IncorrectPassword extends Exception {}
