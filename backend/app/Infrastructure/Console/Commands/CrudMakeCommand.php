<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use Illuminate\Console\Command;

final class CrudMakeCommand extends Command
{
    /**
     * Шаблон для генерации полного имени класса с помощью sprintf().
     * 1 аргумент - название модуля,
     * 2 аргумент - название экшена.
     *
     * В результате получим, например: App\Module\Blog\Action\Create\BlogCreateAction
     */
    private const ACTION_TEMPLATE = 'App\Module\%1$s\Action\%2$s\%1$s%2$sAction';

    private const ACTION_LIST = [
        'Info' => 'Get',
        'List' => 'Get',
        'Create' => 'Post',
        'Update' => 'Post',
        'Delete' => 'Post',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a CRUD actions';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (self::ACTION_LIST as $actionName => $actionMethod) {
            $this->call('make:action', [
                'name' => sprintf(self::ACTION_TEMPLATE, $this->argument('name'), $actionName),
                'route' => strtolower(sprintf('/%s/%s', $this->argument('name'), $actionName)),
                'method' => $actionMethod,
            ]);
        }
    }
}
