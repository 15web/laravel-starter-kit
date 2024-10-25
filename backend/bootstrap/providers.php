<?php

declare(strict_types=1);

use App\Infrastructure\ApiException\ApiExceptionServiceProvider;
use App\Infrastructure\Doctrine\DoctrineServiceProvider;
use App\Infrastructure\Serializer\SerializerServiceProvider;
use App\User\Authentication\Service\AuthenticationServiceProvider;
use App\User\Authorization\Service\AuthorizationServiceProvider;
use Spatie\RouteAttributes\RouteAttributesServiceProvider;

return [
    // Package Service Providers...
    RouteAttributesServiceProvider::class,
    // \Laravel\Tinker\TinkerServiceProvider::class,

    // Application Service Providers...
    DoctrineServiceProvider::class,
    SerializerServiceProvider::class,
    ApiExceptionServiceProvider::class,

    // Modules Service Providers...
    AuthenticationServiceProvider::class,
    AuthorizationServiceProvider::class,
];
