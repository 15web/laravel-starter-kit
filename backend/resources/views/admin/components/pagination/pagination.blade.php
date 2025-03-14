<div class="d-flex justify-content-end">
    <nav class="pe-3">
        <ul class="pagination mb-0">
            @if($offset > 0)
                <li class="page-item">
                    <button type="button" class="page-link"
                            wire:click="$set('offset', {{ $offset < $limit ? 0 : $offset - $limit }})">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </span>
                </li>
            @endif
            @if($offset + $limit < $entityTotal)
                <li class="page-item">
                    <button type="button" class="page-link" wire:click="$set('offset', {{ $offset + $limit }})">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button"
                data-mdb-dropdown-init aria-expanded="false">
            На странице {{ $limit }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @foreach([5, 10, 25, 50] as $perPage)
                <li>
                    <button type="button" class="dropdown-item text-end"
                            wire:click="$set('limit', {{ $perPage }})">{{ $perPage }}</button>
                </li>
            @endforeach
        </ul>
    </div>
</div>