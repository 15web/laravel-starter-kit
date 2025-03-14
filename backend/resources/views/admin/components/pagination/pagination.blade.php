<div class="d-flex justify-content-end">
    <nav class="pe-3">
        <ul class="pagination pagination-sm mb-0">
            @if($offset > 0)
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="$set('offset', {{ $offset < $limit ? 0 : $offset - $limit }})">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="bi bi-arrow-left"></i>
                    </span>
                </li>
            @endif
            @if($offset + $limit < $entityTotal)
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="$set('offset', {{ $offset + $limit }})">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
    <div class="dropdown">
        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
            на страницу {{ $limit }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach([5, 10, 25, 50] as $perPage)
                <li>
                    <button type="button" class="dropdown-item" wire:click="$set('limit', {{ $perPage }})">{{ $perPage }}</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>