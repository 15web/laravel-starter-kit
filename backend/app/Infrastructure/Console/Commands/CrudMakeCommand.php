<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
#[AsCommand(name: 'make:crud', description: 'Make a CRUD actions')]
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

    public function __construct()
    {
        parent::__construct();

        $this->addArgument('name', InputArgument::REQUIRED);
    }

    public function handle(): void
    {
        foreach (self::ACTION_LIST as $actionName => $actionMethod) {
            /** @var string $name */
            $name = $this->argument('name');

            $this->call('make:action', [
                'name' => \sprintf(self::ACTION_TEMPLATE, $name, $actionName),
                'route' => strtolower(\sprintf('/%s/%s', $name, $actionName)),
                'method' => $actionMethod,
            ]);
        }
    }
}
