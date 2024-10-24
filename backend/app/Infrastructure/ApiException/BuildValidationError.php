<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiException;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Tree\Message\DefaultMessage;
use CuyZ\Valinor\Mapper\Tree\Message\Formatter\TranslationMessageFormatter;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;

/**
 * Собирает массив ошибок валидации
 */
final readonly class BuildValidationError
{
    private const string LOCALE = 'en';

    /**
     * @return non-empty-list<string>
     */
    public function __invoke(MappingError $error): array
    {
        $messages = Messages::flattenFromNode(
            node: $error->node(),
        );

        /** @var non-empty-list<string> $allMessages */
        $allMessages = [];

        foreach ($messages->errors() as $message) {
            $message = TranslationMessageFormatter::default()
                ->withTranslations($this->translations())
                ->format($message);

            $messageBody = $message->body();
            if ($message->node()->path() !== '*root*') {
                $messageBody = "{node_path}: {$messageBody}";
            }

            $allMessages[] = $message
                ->withBody($messageBody)
                ->toString();
        }

        return $allMessages;
    }

    /**
     * @return array<non-empty-string, array<non-empty-string, non-empty-string>>
     *
     * @see DefaultMessage
     */
    private function translations(): array
    {
        return [
            'Value {source_value} is not a valid non-empty string.' => [
                self::LOCALE => 'Значение должно быть не пустой строкой',
            ],
            'Value {source_value} does not match any of {allowed_types}.' => [
                self::LOCALE => 'Значение {source_value} не соответствует ни одному из типов: {allowed_types}.',
            ],
        ];
    }
}
