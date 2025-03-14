<div class="mb-3 row">
    <label for="{{ $this->id() }}" class="col-md-3 col-form-label text-end">{{ $label }}</label>
    <div class="col-md-9">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <input type="text" class="form-control-plaintext" wire:model="$parent.data.{{ $name }}.value" readonly/>
            </div>
            <div class="flex-shrink-0 px-2">
                <i class="fa-solid fa-magnifying-glass text-secondary"></i>
            </div>
            <div class="flex-grow-1 position-relative">
                <input type="text" wire:model.live.debounce.1000s="query" class="form-control"/>

                @if($results !== [])
                    <div class="list-group list-group-light list-group-small small bg-white shadow position-absolute w-100">
                        @foreach($results as $resultId => $resultValue)
                            <button type="button" class="list-group-item list-group-item-action px-3"
                                    wire:click.prevent="$set('entity', {id: '{{ $resultId }}', value: '{{ $resultValue }}'})">
                                {{ $resultValue }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>