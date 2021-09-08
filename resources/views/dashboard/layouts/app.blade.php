<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AirBag') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->

    @include('dashboard.layouts.styles')
    @yield('css')

</head>
<body onload="init();">
    <div id="vue-app">
        <div id="swup">
            <v-app id="inspire" light v-cloak>
                @auth
                    @include('dashboard.layouts.sidebar')
                @endauth
                @include('dashboard.layouts.toolbar')
                <div class="transition-fade" max-width="1300px !important;">
                    <v-content>
                        <v-container>
                            @yield('content')
                        </v-container>
                    </v-content>
                </div>
            </v-app>
        </div>
    </div>

    @include('dashboard.layouts.scripts')
    @yield('scripts')

</body>
</html>
