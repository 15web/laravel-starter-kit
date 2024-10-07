<?php

declare(strict_types=1);

return [
    // Automatic registration of routes will only happen if this setting is `true`
    'enabled' => true,

    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     */
    'directories' => [
        app_path('Module') => [
            'prefix' => 'api',
            'middleware' => 'api',
        ],
    ],
];
