<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <div id="header" class="pb-4">
                        <h1 class="h4">
                            <div>{{ $header->title }}</div>
                            @if($header->subtitle)
                                <small>{{ $header->subtitle }}</small>
                            @endif
                        </h1>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>
</html>