<?php

declare(strict_types=1);

use App\Infrastructure\ApiException\ServiceProvider\ApiExceptionRenderServiceProvider;
use App\Infrastructure\ApiRequest\ResolveApiRequestServiceProvider;
use App\Infrastructure\ApiResponse\ResolveApiResponseServiceProvider;
use App\Infrastructure\Doctrine\DoctrineServiceProvider;
use App\Infrastructure\Serializer\SerializerServiceProvider;
use App\Module\Filter\FilterServiceProvider;
use App\Module\News\NewsServiceProvider;
use App\Module\User\Authentication\ServiceProvider\AuthenticationServiceProvider;
use App\Module\User\Authorization\ServiceProvider\AuthorizationServiceProvider;
use Spatie\RouteAttributes\RouteAttributesServiceProvider;

return [
    // Package Service Providers...
    RouteAttributesServiceProvider::class,
    // \Laravel\Tinker\TinkerServiceProvider::class,

    // Application Service Providers...
    ResolveApiRequestServiceProvider::class,
    ResolveApiResponseServiceProvider::class,
    DoctrineServiceProvider::class,
    SerializerServiceProvider::class,
    ApiExceptionRenderServiceProvider::class,

    // Modules Service Providers...
    FilterServiceProvider::class,
    NewsServiceProvider::class,
    AuthenticationServiceProvider::class,
    AuthorizationServiceProvider::class,
];
