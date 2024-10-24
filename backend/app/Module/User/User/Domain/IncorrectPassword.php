<?php

declare(strict_types=1);

namespace App\Module\User\User\Domain;

use Exception;

/**
 * Неправильный пароль
 */
final class IncorrectPassword extends Exception {}
