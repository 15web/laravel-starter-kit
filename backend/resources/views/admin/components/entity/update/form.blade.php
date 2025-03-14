<div>
    <form wire:submit.prevent="saveForm">
        @foreach($formFields as $field)
            <livewire:dynamic-component
                    :is="$field::$component"
                    :name="$field->name"
                    :label="$field->label"
                    :value="$field->value"
                    :options="$field->options"
                    :key="$field->id"
            />
        @endforeach

        <div class="row">
            <div class="offset-md-3 col-md-9">
                @if($errorMessages !== [])
                    <ul class="small">
                        @foreach($errorMessages as $errorMessage)
                            <li class="text-danger-emphasis">{{ $errorMessage }}</li>
                        @endforeach
                    </ul>
                @endif

                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i>
                            <span>Сохранить</span>
                        </button>
                    </li>
                    @if($this->canDeleteEntity)
                        <li class="list-inline-item">
                            @livewire(\Admin\Infrastructure\Component\Update\DeleteButton::class, ['indexEntityComponent' => static::$indexEntityComponent, 'entityId' => $entityId])
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </form>
</div>