<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * Базовый кейс для тестирования
 */
#[TestDox('Базовый кейс для тестирования')]
abstract class FeatureTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    #[Override]
    final public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
