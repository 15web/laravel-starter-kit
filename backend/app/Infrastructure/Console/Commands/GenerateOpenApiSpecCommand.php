<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Yaml\Yaml;

/**
 * Собирает файл спецификации OpenApi
 */
#[AsCommand(name: 'make:openapi', description: 'Собирает файлы спецификации OpenApi')]
final class GenerateOpenApiSpecCommand extends Command
{
    public function handle(): int
    {
        $this->generate(
            resourcesDirs: [base_path('dev/OpenApi/resources/admin')],
            resultFileName: base_path('dev/OpenApi/openapi-admin.yaml'),
        );

        $this->generate(
            resourcesDirs: [base_path('dev/OpenApi/resources/site')],
            resultFileName: base_path('dev/OpenApi/openapi-site.yaml'),
        );

        $this->generate(
            resourcesDirs: [
                base_path('dev/OpenApi/resources/site'),
                base_path('dev/OpenApi/resources/admin'),
            ],
            resultFileName: base_path('dev/OpenApi/openapi.yaml'),
        );

        $this->info('Сборка спецификаций успешно завершена.');

        return Command::SUCCESS;
    }

    /**
     * @param list<string> $resourcesDirs
     */
    private function generate(array $resourcesDirs, string $resultFileName): void
    {
        $resultOpenApiSpec = [];

        foreach ($resourcesDirs as $resourcesDir) {
            if ($resultOpenApiSpec === []) {
                /** @var array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>} $baseOpenApiSpec */
                $baseOpenApiSpec = Yaml::parseFile(
                    filename: \sprintf('%s/../base.yaml', $resourcesDir),
                    flags: Yaml::PARSE_DATETIME,
                );

                /** @var array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>} $commonOpenApiSpec */
                $commonOpenApiSpec = Yaml::parseFile(
                    filename: \sprintf('%s/../common.yaml', $resourcesDir),
                    flags: Yaml::PARSE_DATETIME,
                );

                $resultOpenApiSpec = $this->mergeSpecs(
                    currentOpenApiSpec: $baseOpenApiSpec,
                    newOpenApiSpec: $commonOpenApiSpec,
                );
            }

            $fileNames = scandir($resourcesDir);

            if ($fileNames === false) {
                throw new RuntimeException(\sprintf('scandir can\'t read list of directory: %s', $resourcesDir));
            }

            $fileNames = array_diff($fileNames, ['.', '..']);

            foreach ($fileNames as $fileName) {
                /** @var array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>} $newOpenApiSpec */
                $newOpenApiSpec = Yaml::parseFile(
                    \sprintf('%s/%s', $resourcesDir, $fileName),
                    Yaml::PARSE_DATETIME,
                );

                // todo: check duplicate keys in paths, components and tags

                /** @psalm-suppress InvalidArgument */
                $resultOpenApiSpec = $this->mergeSpecs(
                    currentOpenApiSpec: $resultOpenApiSpec,
                    newOpenApiSpec: $newOpenApiSpec,
                );
            }
        }

        if ($resultOpenApiSpec === []) {
            return;
        }

        $yaml = Yaml::dump(
            input: $resultOpenApiSpec,
            inline: 10,
            indent: 2,
            flags: Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE + Yaml::DUMP_NUMERIC_KEY_AS_STRING,
        );

        // удаляем лишние символы в ref
        $yaml = preg_replace("/([-\w.\/]+.yaml)#/ui", '#', $yaml);

        /** @var non-empty-string $yaml */
        file_put_contents($resultFileName, $yaml);
    }

    /**
     * @param array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>} $currentOpenApiSpec
     * @param array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>} $newOpenApiSpec
     *
     * @return array{tags: array<array-key, mixed>, paths: array<array-key, mixed>, components: array<array-key, mixed>}
     */
    private function mergeSpecs(array $currentOpenApiSpec, array $newOpenApiSpec): array
    {
        $currentOpenApiSpec['tags'] = array_merge_recursive(
            $currentOpenApiSpec['tags'],
            $newOpenApiSpec['tags'],
        );

        $currentOpenApiSpec['paths'] = array_merge_recursive(
            $currentOpenApiSpec['paths'],
            $newOpenApiSpec['paths'],
        );

        $currentOpenApiSpec['components'] = array_merge_recursive(
            $currentOpenApiSpec['components'],
            $newOpenApiSpec['components'],
        );

        return $currentOpenApiSpec;
    }
}
