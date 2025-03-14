<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Contract;

use Admin\Infrastructure\Component\Field\Field;
use Illuminate\Validation\Validator;

/**
 * В компоненте используется форма
 */
trait InteractsWithForm
{
    /**
     * @var array<array-key, mixed>
     */
    public array $data;

    /**
     * @var list<Field>
     */
    public array $formFields;

    /**
     * @var array<string, list<string>>
     */
    public array $rules = [];

    /**
     * @var list<string>
     */
    public array $errorMessages;

    /**
     * @return list<Field>
     */
    abstract public function formFields(): array;

    protected function fillFormAndRules(): void
    {
        $this->formFields = $this->formFields();

        $this->rules = collect($this->formFields)
            ->mapWithKeys(static fn (Field $field) => [
                "data.{$field->name}" => $field->rules,
            ])
            ->all();
    }

    protected function validateFormData(): void
    {
        $this->validate(
            attributes: collect($this->formFields)
                ->mapWithKeys(static fn (Field $field) => [
                    "data.{$field->name}" => $field->label,
                ])
                ->all(),
        );
    }

    protected function collectErrorMessages(): void
    {
        $this->withValidator(function (Validator $validator): void {
            $validator->after(function (Validator $validator): void {
                $this->errorMessages = $validator->messages()->all();
            });
        });
    }
}
