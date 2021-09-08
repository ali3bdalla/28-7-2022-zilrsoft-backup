<!DOCTYPE html>
<html dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name', 'AirBag')}}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('dashboard.layouts.styles')

    @yield('css')

</head>

<body>

<div id="vue-app" class="login-page">
    <div id="swup">
        <v-app id="inspire" light v-cloak>
            @include('dashboard.layouts.toolbar')
            <v-content>
                <v-container>
                    @yield('content')
                </v-container>
            </v-content>
        </v-app>
    </div>
</div>

@include('dashboard.layouts.scripts')
@yield('scripts')
</body>
</html>
