<tr>
    @foreach($table as $column)
        <td>
            @if($column->formatted !== null)
                {{ ($column->formatted)($entity[$column->name]) }}
            @else
                {{ $entity[$column->name] }}
            @endif
        </td>
    @endforeach
    <td class="text-end">
        <ul class="list-inline mb-0">
            <li class="list-inline-item">
                <a href="#" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <button class="btn btn-sm btn-outline-danger"
                        wire:confirm="Are you sure you want to delete this post?"
                        wire:click="$dispatch('delete-entity', { entityId: {{ $entity['id'] }} })">
                    <i class="bi bi-trash"></i>
                </button>
            </li>
        </ul>
    </td>
</tr>