<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title',config('app.name'))</title>
        <script src="{{ asset('js/authentication.js') }}" defer></script>
        <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
        <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    </head>
    <body>

        <div class="bg-blue-400 p-2 shadow-lg">
            <span class="text-white text-2xl">{{ config('app.name') }}</span>
        </div>
        <div class=" pt-10 md:pt-32 text-center object-center">
            @yield('content')
        </div>
    </body>
</html>
