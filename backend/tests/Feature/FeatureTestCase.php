<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * Базовый класс для тестов
 */
#[TestDox('Базовый класс для тестов')]
abstract class FeatureTestCase extends BaseTestCase {}
