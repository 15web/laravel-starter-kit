<?php

declare(strict_types=1);

use App\Infrastructure\ApiException\ApiExceptionServiceProvider;
use App\Infrastructure\Doctrine\DoctrineServiceProvider;
use App\Infrastructure\Serializer\SerializerServiceProvider;
use App\User\Authentication\Service\AuthenticationServiceProvider;
use App\User\Authorization\Service\AuthorizationServiceProvider;
use Dev\Telescope\TelescopeServiceProvider;
use Spatie\RouteAttributes\RouteAttributesServiceProvider;

return [
    ApiExceptionServiceProvider::class,
    DoctrineServiceProvider::class,
    SerializerServiceProvider::class,
    TelescopeServiceProvider::class,
    AuthenticationServiceProvider::class,
    AuthorizationServiceProvider::class,
    RouteAttributesServiceProvider::class,
];
