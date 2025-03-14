<tr>
    @foreach($tableColumns as $column)
        <td>
            @if($column->formatted !== null)
                {{ ($column->formatted)(data_get($entity, $column->name)) }}
            @else
                {{ data_get($entity, $column->name) }}
            @endif
        </td>
    @endforeach
    <td class="text-end">
        <ul class="list-inline mb-0">
            @if($this->canUpdateEntity)
                <li class="list-inline-item">
                    @livewire(\Admin\Infrastructure\Component\Update\UpdateButton::class, ['updateEntityComponent' => $updateEntityComponent, 'entityId' => $entity['id']])
                </li>
            @endif
            @if($this->canDeleteEntity)
                <li class="list-inline-item">
                    <button class="btn btn-floating btn-danger"
                            wire:confirm="Are you sure you want to delete this item?"
                            wire:click="$dispatch('delete-entity', { entityId: {{ $entity['id'] }} })"
                            data-mdb-tooltip-init title="Delete item">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </li>
            @endif
        </ul>
    </td>
</tr>