<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeSplFixedArrayRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withCache(__DIR__.'/../../storage/framework/cache/rector')
    ->withPaths([
        __DIR__.'/../../app',
        __DIR__.'/../../bin',
        __DIR__.'/../../bootstrap/app.php',
        __DIR__.'/../../bootstrap/providers.php',
        __DIR__.'/../../config',
        __DIR__.'/../../dev',
        __DIR__.'/../../resources',
    ])
    ->withParallel()
    ->withPhpSets(php83: true)
    ->withImportNames(importShortClasses: false)
    ->withTypeCoverageLevel(100)
    ->withDeadCodeLevel(50)
    ->withPreparedSets(
        codeQuality: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        rectorPreset: true,
        phpunitCodeQuality: true,
        phpunit: true,
    )
    ->withSets([
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_110,
    ])
    ->withSkip([
        InlineConstructorDefaultToPropertyRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        SimplifyBoolIdenticalTrueRector::class,
        AddParamTypeSplFixedArrayRector::class => [
            __DIR__.'/../PHPCsFixer',
        ],
        PreferPHPUnitThisCallRector::class,
    ]);
