<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap demo</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="{{ asset('vendor/mdb/mdb.min.css') }}" rel="stylesheet"/>
</head>
<body class="py-4">
<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    @include('admin.components.layouts.sidebar')
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="header" class="mb-4">
                        <h1 class="h2 mb-0">{{ $header->title }}</h1>
                        @if($header->subtitle)
                            <div class="text-secondary-emphasis">{{ $header->subtitle }}</div>
                        @endif
                        @includeWhen($header->breadcrumbs !== [], 'admin.components.layouts.breadcrumbs')
                    </div>
                    <div id="content">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('vendor/mdb/mdb.umd.min.js') }}"></script>
<script>
    function reinitMDB() {
        document.querySelectorAll('[data-mdb-dropdown-init]').forEach((e) => new mdb.Dropdown(e))
        document.querySelectorAll('[data-mdb-tooltip-init]').forEach((e) => new mdb.Tooltip(e))
    }

    window.onload = function () {
        reinitMDB();
        Livewire.hook('morphed', reinitMDB);
    };
</script>
</body>
</html>