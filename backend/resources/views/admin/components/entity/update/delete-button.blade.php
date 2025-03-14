<button class="btn btn-danger"
        wire:confirm="Are you sure you want to delete this item?"
        wire:click="$dispatch('delete-entity', { entityId: {{ $entityId }} })"
        data-mdb-tooltip-init title="Delete item">
    <i class="fa-solid fa-trash"></i>
    <span>Удалить</span>
</button>