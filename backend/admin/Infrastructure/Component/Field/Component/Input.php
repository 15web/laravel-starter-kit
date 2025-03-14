<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Field\Component;

/**
 * Компонент поля типа "строка"
 */
final class Input extends FieldComponent
{
    public string $view = 'admin.components.entity.field.input';
}
