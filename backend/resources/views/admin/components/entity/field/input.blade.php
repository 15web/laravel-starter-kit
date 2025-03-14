<div class="mb-3 row">
    <label for="{{ $this->id() }}" class="col-md-3 col-form-label text-end">{{ $label }}</label>
    <div class="col-md-9">
        <input type="{{ $options['type'] ?? 'text' }}" class="form-control" id="{{ $this->id() }}"
               wire:model.blur="$parent.data.{{ $name }}" />
    </div>
</div>