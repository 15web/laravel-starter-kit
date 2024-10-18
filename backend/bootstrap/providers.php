<?php

declare(strict_types=1);

use App\Infrastructure\Doctrine\DoctrineServiceProvider;
use App\Infrastructure\Serializer\SerializerServiceProvider;
use App\Module\Filter\FilterServiceProvider;
use App\Module\User\Authentication\ServiceProvider\AuthenticationServiceProvider;
use App\Module\User\Authorization\ServiceProvider\AuthorizationServiceProvider;
use Spatie\RouteAttributes\RouteAttributesServiceProvider;

return [
    // Package Service Providers...
    RouteAttributesServiceProvider::class,
    // \Laravel\Tinker\TinkerServiceProvider::class,

    // Application Service Providers...
    DoctrineServiceProvider::class,
    SerializerServiceProvider::class,

    // Modules Service Providers...
    FilterServiceProvider::class,
    AuthenticationServiceProvider::class,
    AuthorizationServiceProvider::class,
];
