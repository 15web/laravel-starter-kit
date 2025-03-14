<div>
    @if($this->hasPagination)
        <span>Показано</span>
        @if($this->currentOffset === $this->maxOffset)
            <span>{{ $this->currentOffset }}</span>
        @else
            <span>с {{ $this->currentOffset }} по {{ $this->maxOffset }}</span>
        @endif
        <span>из {{ $entityTotal }}</span>
    @else
        Всего записей: {{ $entityTotal }}
    @endif
</div>