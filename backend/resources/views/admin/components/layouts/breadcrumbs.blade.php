<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="#" wire:navigate>Home</a>
        </li>
        @foreach($header->breadcrumbs as $item)
            @if($item->url)
                <li class="breadcrumb-item">
                    <a href="{{ $item->url }}" wire:navigate>{{ $item->title }}</a>
                </li>
            @else
                <li class="breadcrumb-item">{{ $item->title }}</li>
            @endif
        @endforeach
    </ol>
</nav>