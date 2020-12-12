<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title',config('app.name'))</title>
    <script defer src="{{ asset('js/app_v2.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @includeIf("layouts.web.styles")
</head>
<body>

<div>
    <div class="">
        @inertia
    </div>
</div>


</body>
</html>

