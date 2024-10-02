<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;

/**
 * TODO: Опиши за что отвечает данный класс, какие проблемы решает
 */
#[AsCommand(name: 'make:action', description: 'Create a new action')]
final class ActionMakeCommand extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * Build the class with the given name.
     *
     * Replace the base method and resolve custom stub replacement.
     *
     * @param string $name
     */
    protected function buildClass($name): string
    {
        $replace = [
            '{{ route }}' => $this->argument('route'),
            '{{ method }}' => $this->argument('method'),
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/action.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return non-empty-list<array{0: string, 1: int, 2: string}>
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'Имя класса'],
            ['route', InputArgument::REQUIRED, 'Путь роута'],
            ['method', InputArgument::REQUIRED, 'Метод роута'],
        ];
    }
}
