<?php

declare(strict_types=1);

/**
 * Настройки Doctrine migrations
 *
 * @see https://www.doctrine-project.org/projects/doctrine-migrations/en/3.8/reference/configuration.html#configuration
 */

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 191,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],

    'migrations_paths' => [
        'App\Migrations' => base_path('migrations'),
    ],

    'all_or_nothing' => true,
    'transactional' => true,
    'check_database_platform' => true,
    'organize_migrations' => 'none',
];
