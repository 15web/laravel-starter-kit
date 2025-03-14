<?php

declare(strict_types=1);

namespace Admin\Infrastructure\Component\Field\Component;

/**
 * Компонент автопоиска
 */
final class Autosearch extends FieldComponent
{
    public string $view = 'admin.components.entity.field.autosearch';

    public string $query;

    public array $results;

    public array $entity;

    public function updatedQuery(): void
    {
        $searchQuery = app()->make($this->options['searchQuery']);
        $this->results = ($searchQuery)($this->query);

        $this->dispatch('show-results')->self();
    }

    public function updatedEntity(): void
    {
        $this->reset('query', 'results');

        $this->dispatch('field-updated', $this->name, $this->entity);
    }
}
